<?php

namespace App\Filament\Resources\KeluarResource\Pages;

use App\Filament\Resources\KeluarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Contracts\Support\Htmlable;

class EditKeluar extends EditRecord
{
    protected static string $resource = KeluarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return __('Edit Surat Keluar');
    }
}
