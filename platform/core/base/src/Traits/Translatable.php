<?php

namespace FXC\Base\Traits;

use FXC\Base\Models\Translation;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;

trait Translatable
{
    /**
     * @param $field
     * @param $local
     * @return HigherOrderBuilderProxy|mixed
     */
    public function translation($field, $local = null)
    {
        return Translation::query()
            ->firstOrCreate([
                'locale'      => $local ?? app()->getLocale(),
                'module_id'   => $this->id,
                'module_type' => self::class,
                'field'       => $field,
            ])->value;
    }

    /**
     * @param $value
     * @param $field
     * @return void
     */
    private function setTranslationFieldValue($field, $value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $itemValue) {
                if (in_array($key, get_locales())) {
                    $data = [
                        'module_id'   => $this->id,
                        'module_type' => self::class,
                        'field'       => $field,
                        'locale'      => $key,
                    ];
                    $updateData = array_merge($data, ['value' => $itemValue]);
                    Translation::query()->updateOrCreate($data, $updateData);
                }
            }
        } elseif (is_string($value)) {
            $data = [
                'module_id'   => $this->id,
                'module_type' => self::class,
                'field'       => $field,
                'locale'      => app()->getLocale(),
            ];
            $updateData = array_merge($data, ['value' => $value]);
            Translation::query()->updateOrCreate($data, $updateData);
        } else {

        }
    }

    /**
     * @param $field
     * @return HigherOrderBuilderProxy|mixed
     */
    private function getTranslationFieldValue($field)
    {
        return Translation::query()->firstOrCreate([
            'module_id'   => $this->id,
            'module_type' => self::class,
            'field'       => $field,
            'locale'      => app()->getLocale()
        ])->value;
    }

    /**
     * @param $value
     * @return void
     */
    public function setDescriptionAttribute($value)
    {
        $this->setTranslationFieldValue('description', $value);
    }

    /**
     * @return HigherOrderBuilderProxy|mixed
     */
    public function getDescriptionAttribute()
    {
        return $this->getTranslationFieldValue('description');
    }
}
