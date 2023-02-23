<?php

namespace App\Models;

use FXC\Base\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;

class Setting extends Model
{
    use Userstamps, BaseTrait;

    protected $fillable = [];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // ... code here
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            // ... code here
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
}
