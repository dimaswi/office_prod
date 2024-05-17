<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Revision extends Model
{
    use HasFactory, HasUuids;

    protected $table ='revisi_surat_keluar';

    protected $fillable = [
        'surat',
        'revisi',
        'user',
    ];

    public function SuratKeluar() : BelongsTo
    {
        return $this->belongsTo(Keluar::class, 'surat', 'id');
    }

    public function User() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }
}
