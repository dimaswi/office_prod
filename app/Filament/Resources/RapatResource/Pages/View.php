<?php

namespace App\Filament\Resources\RapatResource\Pages;

use App\Filament\Resources\RapatResource;
use App\Models\Rapat;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = RapatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('daftar_hadir')->label('Daftar Hadir')->icon('heroicon-o-user-group')->url(
                function (Rapat $record) {
                    return RapatResource::getUrl('daftar_hadir', ['record' => $record->id]);
                },
            ),
            Actions\Action::make('notulen')->icon('heroicon-o-clipboard-document-list')->url(
                function (Rapat $record) {
                    return RapatResource::getUrl('notulen', ['record' => $record->id]);
                },
            ),
            Actions\EditAction::make()->label('Edit')->icon('heroicon-o-pencil-square')->color('warning'),
        ];
    }
}
