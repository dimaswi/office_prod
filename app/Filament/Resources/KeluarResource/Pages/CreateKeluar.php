<?php

namespace App\Filament\Resources\KeluarResource\Pages;

use App\Filament\Resources\KeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateKeluar extends CreateRecord
{
    protected static string $resource = KeluarResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Buat Surat Keluar');
    }
}
