<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;
use Wildside\Userstamps\Userstamps;

class Material extends Model
{
    use HasFactory, SoftDeletes, Userstamps, LaravelVueDatatableTrait;

    protected $appends = [
        'inventory_packages_amount',
        'inventory_pieces_amount',
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

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getIsOutOfStockAttribute()
    {
        return $this->inventory_packages_amount <= $this->min_packages_count
            /*|| $this->inventory_pieces_amount <= $this->min_pieces_count*/;
    }

    public function getInventoryPackagesAmountAttribute()
    {
        return (int)($this->orders()
                ->where('type', 'in')
                ->where('status', 'in_stock')
                ->sum('packages_amount')
            - $this->orders()
                ->where('type', 'out')
                ->whereNull('status')
                ->sum('packages_amount'));
    }

    public function getInventoryPiecesAmountAttribute()
    {
        return (int)($this->orders()
                ->where('type', 'in')
                ->where('status', 'in_stock')
                ->sum('pieces_amount')
            - $this->orders()
                ->where('type', 'out')
                ->whereNull('status')
                ->sum('pieces_amount'));
    }

    public static function outOfStock(): Collection
    {
        return static::query()->get()->filter(function ($model) {
            return $model->is_out_of_stock;
        });
    }
}
