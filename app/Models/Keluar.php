<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Keluar extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'surat_keluars';

    protected $fillable = [
        'nomor',
        'jenis_surat',
        'nomor_surat',
        'tanggal_surat',
        'perihal_surat',
        'sifat_surat',
        'penerima_surat',
        'isi_surat',
        'unit',
        'tanda_tangan',
        'jabatan',
        'user',
    ];

    public function Tandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tanda_tangan');
    }

    public function Unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit', 'id');
    }

    public function JenisSurat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }

    public function Revisi(): HasMany
    {
        return $this->hasMany(Revision::class, 'surat', 'id');
    }

    public function Pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
