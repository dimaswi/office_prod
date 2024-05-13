<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rapat extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rapats';

    protected $fillable = [
        'nomor',
        'unit_id',
        'nomor_rapat',
        'user_id',
        'agenda_rapat',
        'tempat_rapat',
        'tanggal_rapat',
        'jam_rapat',
        'hari_rapat',
        'starts_at',
        'ends_at',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'undangan_rapats', 'rapat_id', 'user_id')->withTimestamps();
    }

    public function pimpinan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function notulen(): HasMany
    {
        return $this->hasMany(Notulen::class, 'rapat_id', 'id');
    }
}
