<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Masuk extends Model
{
    use HasFactory, HasUuids;

    protected $table ='surat_masuks';

    protected $fillable = [
        'jenis_surat',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_diterima',
        'sifat_surat',
        'lampiran',
        'dokumen_surat',
        'pengirim_surat',
        'perihal_surat',
        'isi_surat',
        'disposisi',
    ];

    protected function casts(): array
    {
        return [
            'disposisi' => 'array',
        ];
    }

    public function tindak_lanjuts(): HasMany
    {
        return $this->hasMany(TLMasuk::class, 'surat_masuk_id', 'id');
    }
}
