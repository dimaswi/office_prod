<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}
