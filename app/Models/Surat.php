<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory, HasUuids;

    protected $table ='jenis_surats';

    protected $fillable = [
        'nama_surat',
        'kode_surat',
    ];
}
