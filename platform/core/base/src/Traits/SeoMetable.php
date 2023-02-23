<?php

namespace FXC\Base\Traits;

use FXC\Base\Models\SeoMeta;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait SeoMetable
{
    /**
     * @return morphMany
     */
    public function meta_tags(): MorphMany
    {
        return $this->morphMany(SeoMeta::class, 'module');
    }

    /**
     * @return array
     */
    public function seo_meta(): array
    {
        $data = group_by('locale', $this->meta_tags()->get()->toArray());
        foreach ($data as $locale => $item) {
            $data[$locale] = $item[0] ?? [];
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getSeoAttribute(): array
    {
        $seo = $this->meta_tags()->local()->first();
        $seo = collect($seo)->only(['slug', 'title', 'description', 'keywords']);
        return $seo->toArray();
    }

    /**
     * @return string
     */
    public function getSeoSlugAttribute(): string
    {
        return ($this->seo['slug'] ?? $this->slug) ?? $this->id;
    }

    /**
     * @return void
     */
    public static function bootSeoMetable()
    {
        static::created(function ($model) {
            foreach (get_locales() as $local) {
                create_seo_meta($model->title, $model->summary, $model->id, self::class, $local);
            }
        });
    }
}
