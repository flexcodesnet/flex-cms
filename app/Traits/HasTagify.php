<?php

namespace App\Traits;

trait HasTagify
{
    public function setAdditionalTagsAttribute($value)
    {
        $this->attributes['keywords'] = tagify_to_values($value);
    }
}
