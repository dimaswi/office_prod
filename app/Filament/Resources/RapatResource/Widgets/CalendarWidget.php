<?php

namespace App\Filament\Resources\RapatResource\Widgets;

use App\Filament\Resources\RapatResource;
use App\Models\Rapat;
use App\Models\Unit;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $fetchInfo): array
    {
        // dd(Rapat::query()
        // ->where('starts_at', '>=', $fetchInfo['start'])
        // ->where('ends_at', '<=', $fetchInfo['end'])
        // ->get());
        return Rapat::query()
            ->where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            ->map(
                fn (Rapat $event) => [
                    'title' => $event->agenda_rapat,
                    'start' => $event->starts_at,
                    'end' => $event->ends_at,
                    'url' => RapatResource::getUrl(name: 'view', parameters: ['record' => $event]),
                    'shouldOpenUrlInNewTab' => true
                ]
            )
            ->all();
    }

}
