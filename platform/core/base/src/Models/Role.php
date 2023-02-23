<?php

namespace FXC\Base\Models;

use FXC\Base\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Role extends Model
{
    use SoftDeletes, Userstamps, BaseTrait;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // ... code here
            $model->slug = $model->title;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            // ... code here
            $model->slug = $model->title;
        });

        self::updated(function ($model) {
            // ... code here
        });

        self::deleting(function ($model) {
            // ... code here
        });

        self::deleted(function ($model) {
            // ... code here
        });
    }

    /**
     * @return BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }
}
