<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Category extends Model
{
    use SoftDeletes, Userstamps;

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

    public function materials()
    {
        return $this->belongsToMany(Material::class);
    }

    public function manufacturers()
    {
        return $this->belongsToMany(Manufacturer::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }
}
