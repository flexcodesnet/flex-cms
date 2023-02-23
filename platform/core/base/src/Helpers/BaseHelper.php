<?php

namespace FXC\Base\Helpers;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;

class BaseHelper
{
    /**
     * @param  Carbon  $timestamp
     * @param  string|null  $format
     * @return string
     */
    public function formatTime(Carbon $timestamp, ?string $format = 'j M Y H:i'): string
    {
        $first = Carbon::create(0000, 0, 0, 00, 00, 00);

        if ($timestamp->lte($first)) {
            return '';
        }

        return $timestamp->format($format);
    }

    /**
     * @param  string|null  $date
     * @param  string|null  $format
     * @return string
     */
    public function formatDate(?string $date, ?string $format = null): ?string
    {
        if (empty($format)) {
            $format = config('core.base.general.date_format.date');
        }

        if (empty($date)) {
            return $date;
        }

        return $this->formatTime(Carbon::parse($date), $format);
    }

    /**
     * @param  string|null  $date
     * @param  string|null  $format
     * @return string|null
     */
    public function formatDateTime(?string $date, string $format = null): ?string
    {
        if (empty($format)) {
            $format = config('core.base.general.date_format.date_time');
        }

        if (empty($date)) {
            return $date;
        }

        return $this->formatTime(Carbon::parse($date), $format);
    }

    /**
     * @param  int  $bytes
     * @param  int  $precision
     * @return string
     */
    public function humanFilesize(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'kB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return number_format($bytes, $precision, ',', '.').' '.$units[$pow];
    }

    /**
     * @param  string  $file
     * @param  bool  $convertToArray
     * @return array|bool|mixed|null
     */
    public static function getFileData(string $file, bool $convertToArray = true)
    {
        $file = File::get($file);
        if (!empty($file)) {
            if ($convertToArray) {
                return json_decode($file, true);
            }

            return $file;
        }

        if (!$convertToArray) {
            return null;
        }

        return [];
    }

    /**
     * @param  string  $path
     * @param  string|array  $data
     * @param  bool  $json
     * @return bool
     */
    public function saveFileData(string $path, $data, bool $json = true): bool
    {
        try {
            if ($json) {
                $data = $this->jsonEncodePrettify($data);
            }

            if (!File::isDirectory(File::dirname($path))) {
                File::makeDirectory(File::dirname($path), 493, true);
            }

            File::put($path, $data);

            return true;
        } catch (Exception $exception) {
            info($exception->getMessage());

            return false;
        }
    }

    /**
     * @param  array|string  $data
     * @return string
     */
    public function jsonEncodePrettify($data): string
    {
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param  string  $path
     * @param  array  $ignoreFiles
     * @return array
     */
    public static function scanFolder(string $path, array $ignoreFiles = []): array
    {
        try {
            if (File::isDirectory($path)) {
                $data = array_diff(scandir($path), array_merge(['.', '..', '.DS_Store'], $ignoreFiles));
                natsort($data);

                return $data;
            }

            return [];
        } catch (Exception $exception) {
            return [];
        }
    }

    /**
     * @return string
     */
    public static function getAdminPrefix(): string
    {
        return config('core.base.general.admin_dir');
    }

    /**
     * @return string
     */
    public static function getAdminMasterLayoutTemplate(): string
    {
        return  'panel.layout';
//        return apply_filters('base_filter_admin_master_layout_template', 'panel.include.datatable.index');
    }

    /**
     * @return string
     */
    public function siteLanguageDirection(): string
    {
        return apply_filters(BASE_FILTER_SITE_LANGUAGE_DIRECTION, setting('locale_direction', 'ltr'));
    }

    /**
     * @return string
     */
    public function adminLanguageDirection(): string
    {
        $direction = session('admin_locale_direction', setting('admin_locale_direction', 'ltr'));

        return apply_filters(BASE_FILTER_ADMIN_LANGUAGE_DIRECTION, $direction);
    }

    /**
     * @param  int|null  $pageId
     * @return bool
     */
    public function isHomepage(?int $pageId = null): bool
    {
        $homepageId = $this->getHomepageId();

        return $pageId && $homepageId && $pageId == $homepageId;
    }

    /**
     * @return string
     */
    public function getHomepageId(): ?string
    {
        return theme_option('homepage_id', setting('show_on_front'));
    }

    /**
     * @param  Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $table
     * @return bool
     */
    public function isJoined($query, string $table): bool
    {
        $joins = $query->getQuery()->joins;

        if ($joins == null) {
            return false;
        }

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getRichEditor(): string
    {
        return setting('rich_editor', config('core.base.general.editor.primary'));
    }

    /**
     * @param  string|null  $url
     * @param  string|array  $key
     * @return false|string
     */
    public function removeQueryStringVars(?string $url, $key)
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        foreach ($key as $item) {
            $url = preg_replace('/(.*)(?|&)'.$item.'=[^&]+?(&)(.*)/i', '$1$2$4', $url.'&');
            $url = substr($url, 0, -1);
        }

        return $url;
    }

    /**
     * @param  string|null  $value
     * @return string
     */
    public function cleanEditorContent(?string $value): string
    {
        $value = str_replace('<span class="style-scope yt-formatted-string" dir="auto">', '', $value);

        return htmlentities($this->clean($value));
    }

    /**
     * @return string
     */
    public function getPhoneValidationRule(): string
    {
        return config('core.base.general.phone_validation_rule');
    }

    /**
     * @param  Collection|array  $collection
     * @param  string  $searchTerms
     * @param  string  $column
     * @return Collection
     */
    public function sortSearchResults($collection, string $searchTerms, string $column): Collection
    {
        if (!$collection instanceof Collection) {
            $collection = collect($collection);
        }

        return $collection->sortByDesc(function ($item) use ($searchTerms, $column) {
            $searchTerms = explode(' ', $searchTerms);

            // The bigger the weight, the higher the record
            $weight = 0;

            // Iterate through search terms
            foreach ($searchTerms as $term) {
                if (strpos($item->{$column}, $term) !== false) {
                    // Increase weight if the search term is found
                    $weight += 1;
                }
            }

            return $weight;
        });
    }

    /**
     * @return string[]
     */
    public function getDateFormats(): array
    {
        $formats = [
            'Y-m-d',
            'Y-M-d',
            'y-m-d',
            'm-d-Y',
            'M-d-Y',
        ];

        foreach ($formats as $format) {
            $formats[] = str_replace('-', '/', $format);
        }

        $formats[] = 'M d, Y';

        return $formats;
    }

    /**
     * @param  string|null|array  $dirty
     * @param  array|string|null  $config
     * @return mixed
     */
    public function clean($dirty, $config = null)
    {
        if (config('core.base.general.enable_less_secure_web', false)) {
            return $dirty;
        }

        return clean($dirty ?: '', $config);
    }

    /**
     * @param  string|null|array  $dirty
     * @param  array|string|null  $config
     * @return HtmlString
     */
    public function html($dirty, $config = null): HtmlString
    {
        return new HtmlString($this->clean($dirty, $config));
    }

    /**
     * @param  string  $color
     * @param  float  $opacity
     * @return string
     */
    public function hexToRgba(string $color, float $opacity = 1): string
    {
        $rgb = implode(',', $this->hexToRgb($color));

        if ($opacity == 1) {
            return 'rgb('.$rgb.')';
        }

        return 'rgba('.$rgb.', '.$opacity.')';
    }

    /**
     * @param  string  $color
     * @return array
     */
    public function hexToRgb(string $color): array
    {
        [$red, $green, $blue] = sscanf($color, '#%02x%02x%02x');

        $blue = $blue === null ? 0 : $blue;

        return compact('red', 'green', 'blue');
    }

    /**
     * @param  string  $key
     * @param $value
     * @return $this
     */
    public function iniSet(string $key, $value): self
    {
        if (config('core.base.general.enable_ini_set', true)) {
            try {
                @ini_set($key, $value);
            } catch (Exception $exception) {
                return $this;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function maximumExecutionTimeAndMemoryLimit(): self
    {
        $this->iniSet('max_execution_time', -1);
        $this->iniSet('memory_limit', -1);

        return $this;
    }

    /**
     * @param  string|null  $string
     * @return array|string|string[]|null
     */
    public function removeSpecialCharacters(?string $string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    /**
     * @param  string  $name
     * @return string
     */
    public function getInputValueFromQueryString(string $name): string
    {
        $value = request()->input($name);

        if (!is_string($value)) {
            return '';
        }

        return $value;
    }

    /**
     * @param  string|null  $content
     * @return string|null
     */
    public function cleanShortcodes(?string $content): ?string
    {
        if (!$content) {
            return $content;
        }

        $content = $this->clean($content);

        $shortcodeCompiler = shortcode()->getCompiler();

        return $shortcodeCompiler->strip($content, []);
    }

    public function stringify($content): ?string
    {
        if (is_string($content)) {
            return $content;
        }

        if (is_array($content)) {
            return json_encode($content);
        }

        return null;
    }
}
