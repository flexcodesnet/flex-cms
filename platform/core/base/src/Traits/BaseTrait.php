<?php

namespace FXC\Base\Traits;


use FXC\Base\Models\ListOption;
use App\Support\Str;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BaseTrait
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return app(self::class)->getTable();
    }

    /**
     * @return Application|UrlGenerator|string
     */
    public function getUrlAttribute()
    {
        return url("{$this->url_prefix}/{$this->id}");
    }

    /**
     * @return mixed|string
     */
    public function getFolderPathAttribute()
    {
        return $this->folder;
    }

    /**
     * @return Application|UrlGenerator|string
     */
    public function getImageUrlAttribute()
    {
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset("storage/{$this->folder}/{$this->image}");
    }

    /**
     * @return string
     */
    public static function getFieldClassName(): string
    {
        $name = class_basename(self::class);
        $namespace = "FXC\\Base\\Table";
        $className = "{$namespace}\\{$name}Field";
        return $className;
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeLocal($query)
    {
        return $query->where('locale', app()->getLocale());
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', 1);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    /**
     * @param $foreignKey
     * @param  bool  $withTable
     *
     * @return BelongsTo
     */
    public function belongsToList($foreignKey, bool $withTable = false): BelongsTo
    {
        $foreignKey = $withTable ? $this->getTable().".{$foreignKey}" : $foreignKey;

        return $this->belongsTo(ListOption::class, $foreignKey);
    }

    /**
     * @param $related
     * @param  null  $foreignKey
     * @param  null  $ownerKey
     * @param  null  $relation
     * @param  bool  $withTable
     *
     * @return BelongsTo
     */
    public function belongsToRelation($related, $foreignKey = null, bool $withTable = false, $ownerKey = null, $relation = null)
    {
        $foreignKey = $withTable ? $this->getTable().".{$foreignKey}" : $foreignKey;

        return $this->belongsTo($related, $foreignKey, $ownerKey, $relation);
    }

    /**
     * @return void
     */
    public static function bootBaseTrait()
    {
        static::creating(function ($model) {
            if (auth()->check() and array_key_exists('created_by', $model->toArray())) {
                $model->created_by = auth()->id();
            }
        });

        static::created(function ($model) {
            if (array_key_exists('slug', $model->toArray()) and !$model->slug) {
                $model->slug = Str::slug($model->title);
            }
        });


        static::updating(function ($model) {
            if (auth()->check() and array_key_exists('modified_by', $model->toArray())) {
                $model->modified_by = auth()->id();
            }
            if (array_key_exists('slug', $model->toArray()) and !$model->slug) {
                $model->slug = Str::slug($model->title);
            }
        });

        static::deleting(function ($model) {
            if (auth()->check() and array_key_exists('deleted_by', $model->toArray())) {
                $model->deleted_by = auth()->id();
            }
            $model->timestamps = false;
        });

    }
}
