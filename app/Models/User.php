<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Mass assignable
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'company_id',
        'is_active',
    ];

    // Hidden attributes
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attribute casting
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // 🔗 Relationship: User belongs to Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // Role constants
    public const ROLE_ADMIN          = 'admin';
    public const ROLE_CLIENT_SERVICE = 'client_service';
    public const ROLE_CLIENT_LIMITED = 'client_limited';
    public const ROLE_COMMERCIAL     = 'commercial';
    public const ROLE_PLANNER        = 'planner';
    public const ROLE_INSTALLER      = 'poseur';
    public const ROLE_ACCOUNTANT     = 'comptable';
    public const ROLE_SUPERADMIN     = 'superadmin';

    // Role labels for selection/dropdowns
    public static function roles()
    {
        return [
            self::ROLE_ADMIN          => 'Administrateur',
            self::ROLE_CLIENT_SERVICE => 'Service client',
            self::ROLE_CLIENT_LIMITED => 'Service client limité',
            self::ROLE_COMMERCIAL     => 'Commercial',
            self::ROLE_PLANNER        => 'Service Devis, commande et RDV',
            self::ROLE_INSTALLER      => 'Poseur',
            self::ROLE_ACCOUNTANT     => 'Comptable',
            self::ROLE_SUPERADMIN     => 'Super Administrateur',
        ];
    }

    // Role check: single
    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }

    // Role check: any of many
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    public function getRoleLabelAttribute()
    {
        $roles = self::roles();
        return $roles[$this->role] ?? $this->role;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class, 'sender_id');
    }

    public function getUnitsAttribute(): int
    {
        // Backwards compatibility for old views that call $user->units
        return (int) ($this->company->units ?? 0);
    }




public function scopeSupportUsers($q)
{
    return $q->whereIn('role', [self::ROLE_SUPERADMIN, self::ROLE_CLIENT_SERVICE])
             ->where('is_active', true);
}

public function canSeeAllConversations(): bool
{
    return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_CLIENT_SERVICE], true);
}

public function isSupport(): bool
{
    return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_CLIENT_SERVICE], true);
}

}