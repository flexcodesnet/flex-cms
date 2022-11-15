<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use JamesDordoy\LaravelVueDatatable\Traits\LaravelVueDatatableTrait;

class Order extends Model
{
    use HasFactory, Userstamps, LaravelVueDatatableTrait;

    protected $dataTableColumns = [
        'id' => [
            'searchable' => false,
            "orderable" => false,
        ],
        'packages_amount' => [
            'searchable' => false,
            "orderable" => true,
        ],
        'pieces_amount' => [
            'searchable' => false,
            "orderable" => true,
        ],
        'created_at' => [
            'searchable' => true,
            "orderable" => true,
        ],
        'type' => [
            'searchable' => true,
            "orderable" => true,
        ],
    ];

    protected $dataTableRelationships = [
        "belongsTo" => [
            "material" => [
                "model" => Material::class,
                "foreign_key" => "material_id",
                "columns" => [
                    "name" => [
                        "searchable" => false,
                        "orderable" => true,
                    ],
                ],
            ],
            "supplier" => [
                "model" => Supplier::class,
                "foreign_key" => "supplier_id",
                "columns" => [
                    "name" => [
                        "searchable" => false,
                        "orderable" => true,
                    ],
                ],
            ],
            "manufacturer" => [
                "model" => Manufacturer::class,
                "foreign_key" => "manufacturer_id",
                "columns" => [
                    "name" => [
                        "searchable" => false,
                        "orderable" => true,
                    ],
                ],
            ],
        ],
    ];

    public static function enum($key)
    {
        return [
            'type' => [
                'in',
                'out',
            ],
            'status' => [
                'on_hold',
                'ordered',
                'in_stock',
            ],
        ][$key];
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // ... code here
            if ($model->type == 'out') {
                $model->status = null;
            }
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            // ... code here
            if ($model->type == 'out') {
                $model->status = null;
            }
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

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
