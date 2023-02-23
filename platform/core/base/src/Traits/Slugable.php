<?php

namespace FXC\Base\Traits;

use App\Support\Str;

trait Slugable
{
    /**
     * @param $value
     * @param  string  $separator
     * @return void
     */
    public function setSlugAttribute($value, string $separator = '-')
    {
        $value = $value ?? $this->slug;
        if (!$value) {
            $value = $this->title;
        }
//        if (app()->getLocale() == 'ar') {
//            $separator = '_';
//        }

        $value = slug($value, $separator);

        $this->attributes['slug'] = $value;
        if (empty($value)) {
            $this->attributes['slug'] = Str::random(16);
        }
    }


}
