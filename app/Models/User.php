<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // admin atau irban
        'irban_type',  // irban1, irban2, irban3, irban4, irbansus
    ];

    /**
     * Hidden attributes for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Izin akses ke Panel Filament.
     * Anda bisa membatasi siapa saja yang boleh login ke dashboard di sini.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Contoh: Semua user dengan email terverifikasi atau role tertentu bisa masuk
        return true; 
    }

    /**
     * Helper untuk cek apakah user adalah Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Helper untuk cek apakah user adalah Irban
     */
    public function isIrban(): bool
    {
        return $this->role === 'irban';
    }
}