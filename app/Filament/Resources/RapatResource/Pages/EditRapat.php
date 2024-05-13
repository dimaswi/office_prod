<?php

namespace App\Filament\Resources\RapatResource\Pages;

use App\Filament\Resources\RapatResource;
use App\Models\Rapat;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRapat extends EditRecord
{
    protected static string $resource = RapatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Detail')->icon('heroicon-o-information-circle')->url(
                function (Rapat $record) {
                    return RapatResource::getUrl('view', ['record' => $record->id]);
                },
            ),
            Actions\DeleteAction::make(),
        ];
    }
}
