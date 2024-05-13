<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'units';

    protected $fillable = [
        'nama_unit',
        'kode_unit',
        'kepala_unit',
    ];

    public function kepala(): BelongsTo
    {
        return $this->belongsTo(User::class, 'kepala_unit');
    }

    public function karyawan(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
