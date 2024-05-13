<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TLMasuk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'tindak_lanjut_surat_masuk';

    protected $fillable = [
        'surat_masuk_id',
        'catatan',
        'user_id'
    ];

    public function surat_masuk(): BelongsTo
    {
        return $this->belongsTo(Masuk::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
