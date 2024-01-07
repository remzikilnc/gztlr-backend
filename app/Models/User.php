<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;
use Spatie\Permission\Traits\HasRoles;

class User extends BaseUserModel//, MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, Searchable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute(): string
    {
        return ucfirst($this->attributes['first_name']) . ' ' . ucfirst($this->attributes['last_name']);
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function scopeSearch($query, $value)
    {
        return $query->where('first_name', 'like', '%'.$value.'%')
            ->orWhere('last_name', 'like', '%'.$value.'%')
            ->orWhere('email', 'like', '%'.$value.'%');
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'display_name' => $this->display_name,
            'email' => $this->email,
        ];
    }

    /**
     * @return array
     * Array key is relation name
     * Array value is permission name
     * Array value can be blank if no permission is required
     * @example 'relation_name' => 'permission_name'
     */
    public static function getLoadableRelations(): array
    {
        return [
            'roles' => 'users.roles.view',
        ];
    }
}
