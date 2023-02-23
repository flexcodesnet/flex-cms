<?php

namespace FXC\Base\Models;

use App\Traits\HasTreeView;
use FXC\Base\Supports\Str;
use FXC\Base\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Permission extends Model
{
    use SoftDeletes, HasTreeView, Userstamps, BaseTrait;

    protected $fillable = [
        'title',
    ];

    protected $hidden = [
        'parent_id',
        'created_at',
        'updated_at',
        'deleted_at',
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

    public static function childrenIds()
    {
        return static::query()
            ->whereNotIn(
                'id',
                remove_null(static::query()->distinct()->pluck('parent_id')->toArray())
            )->pluck('id')->toArray();
    }

    public function scopeParents($query)
    {
        return $query->where('parent_id', null);
    }

    public function scopeChildren($query)
    {
        return $query->where('parent_id', $this->id);
    }

    public function getParentAttribute()
    {
        return static::query()->find($this->parent_id);
    }

    public function getNeedChildrenAttribute()
    {
        return !isset($this->parent);
    }

    public function getChildrenAttribute()
    {
        return static::query()->where('parent_id', $this->id)->get();
    }

    public function getRouteNameAttribute()
    {
//        return $this->slug;
        $temp = explode("-", $this->slug);
        if (count($temp) == 1) {
            return sprintf('panel.%s', $temp[0]);
        }

        if (count($temp) == 2) {
            return sprintf('panel.%s.%s', $temp[1], $temp[0]);
        }

        if (count($temp) == 3) {
            return sprintf('panel.%s_%s.%s', $temp[1], $temp[2], $temp[0]);
        }
    }

    public function setSlugAttribute($value)
    {
        if (!isset($this->parent_id)) {
            $this->attributes['slug'] = str_replace(' ', '-', strtolower($value));
        } else {
            $this->attributes['slug'] = str_replace(' ', '-', strtolower($value)).'-'.$this->parent->slug;
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function check($route_name)
    {
        if ('panel.index' == $route_name) {
            return true;
        }

        $route_name = Str::replaceAll(['index', 'show', 'data'], 'view', $route_name);
        $route_name = Str::replaceAll(['create'], 'add', $route_name);
        $route_name = Str::replaceAll([
            'update',
            'image.delete',
            'images.upload',
        ], 'edit', $route_name);

        if (Str::contains($route_name, 'dashboard.')) {
            $route_name = 'panel.dashboard';
        }

        switch ($route_name) {
            case 'panel.settings.view':
                $route_name = 'panel.settings';
                break;
            case 'panel.dashboard.view':
                $route_name = 'panel.dashboard';
                break;
            case 'panel.languages.view':
                $route_name = 'panel.languages';
                break;
        }

//        dd($route_name);
//        dd(auth()->user()->role->permissions->pluck('route_name')->toArray());
//        dd(in_array($route_name, auth()->user()->role->permissions->pluck('route_name')->toArray()));
        if (auth()->check()) {
            return auth()->user()->check($route_name);
        }
        return false;
    }
}
