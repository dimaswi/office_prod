<?php

namespace App\Filament\Resources\RapatResource\Pages;

use App\Filament\Resources\RapatResource;
use App\Models\Rapat;
use App\Models\UndanganRapat;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DaftarHadirRapats extends Page implements HasForms, HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;
    use InteractsWithForms;

    protected static string $resource = RapatResource::class;

    protected static string $view = 'filament.resources.rapat-resource.pages.daftar-hadir-rapats';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                UndanganRapat::where('rapat_id', $this->record->id)
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama'),
                IconColumn::make('status')->label('Kehadiran')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-o-check-circle',
                        '0' => 'heroicon-o-minus-circle',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'success',
                        '0' => 'danger',
                        default => 'gray',
                    })
            ])
            ->actions([
                Action::make('Hadir')
                    // ->hidden(function (UndanganRapat $record): bool {
                    //     $visible = auth()->user()->hasRole('Logistik');
                    //     return $visible;
                    // })
                    ->requiresConfirmation()
                    ->action(function (UndanganRapat $record, array $data): void {
                        UndanganRapat::where('id', $record->id)->update([
                            'status' => 1
                        ]);
                    }),
            ]);
    }
}
