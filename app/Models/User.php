<?php

namespace App\Models;

use App\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Wildside\Userstamps\Userstamps;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Userstamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    public function role()
    {
        return $this->belongsTo(Role::class)->with('permissions');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = Str::slug($value, '_');
    }

    public function check($route_name)
    {
        return in_array($route_name, $this->role->permissions->pluck('route_name')->toArray());
    }
}
