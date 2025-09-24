<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use CrudTrait;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['password_confirmation'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getProfilePicture()
    {
        return Storage::url($this->profile_picture);
    }

    public function setPasswordAttribute($value)
    {
        // Only hash the password if a value is provided
        if (! empty($value) && ! empty(trim($value))) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * Get the properties for the user
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'user_id');
    }

    /**
     * Get the projects for the user
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id');
    }

    /**
     * Get the customers for the user
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customers::class, 'refId');
    }

    /**
     * Get the partners for the user
     */
    public function partners(): HasMany
    {
        return $this->hasMany(Partners::class, 'user_id');
    }
}
