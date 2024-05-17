<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jabatan',
        'unit',
    ];

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@klinik.com') && $this->hasVerifiedEmail();
    }

    public function kepala(): HasOne
    {
        return $this->hasOne(Unit::class, 'kepala_unit');
    }

    public function unit_kerja(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit');
    }

    public function tlmasuk(): BelongsTo
    {
        return $this->belongsTo(TLMasuk::class);
    }

    public function Suratkeluar(): HasMany
    {
        return $this->hasMany(Keluar::class, 'tanda_tangan');
    }

    public function Revisi() : HasMany
    {
        return $this->hasMany(Revision::class);
    }

    public function keluar() : HasMany
    {
        return $this->hasMany(Keluar::class);
    }
}
