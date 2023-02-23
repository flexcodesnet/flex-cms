<?php

namespace FXC\Base\Traits;

use FXC\Base\Models\Attachment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Attachable
{
    /**
     * @return MorphMany
     */
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
