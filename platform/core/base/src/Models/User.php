<?php

namespace FXC\Base\Models;

use FXC\Base\Supports\Str;
use FXC\Base\Traits\BaseTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Wildside\Userstamps\Userstamps;

class User extends Authenticatable
{
    use BaseTrait, SoftDeletes, Userstamps;

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->with('permissions');
    }

    /**
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param $value
     * @return void
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = Str::slug($value, '_');
    }

    /**
     * @param $route_name
     * @return bool
     */
    public function check($route_name): bool
    {
        return in_array($route_name, $this->role->permissions->pluck('route_name')->toArray());
    }


    /**
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            // ... code here
            $model->username = $model->name;
        });

        self::created(function ($model) {
            // ... code here
        });

        self::updating(function ($model) {
            // ... code here
            $model->username = $model->name;
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
