<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

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

    public static function enum($key)
    {
        return [
            'preferred_ordering_method' => [
                'eshop',
                'email',
                'phone',
            ],
        ][$key];
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
