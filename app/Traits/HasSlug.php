<?php

namespace App\Traits;

use App\Support\Str;

trait HasSlug
{
    public function setSlugAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['slug'] = (string)Str::uuid();
            return;
        }
        $this->attributes['slug'] = substr(Str::slug($value), 0, 191);
    }

    public function scopeFindOrFailBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->firstOrFail();
    }

    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }
}
