<?php

namespace App\Filament\Resources\RapatResource\Pages;

use App\Filament\Resources\RapatResource;
use App\Filament\Resources\RapatResource\Widgets\CalendarWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRapats extends ListRecords
{
    protected static string $resource = RapatResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Rapat')->color('success')->icon('heroicon-o-plus-circle'),
        ];
    }
}
