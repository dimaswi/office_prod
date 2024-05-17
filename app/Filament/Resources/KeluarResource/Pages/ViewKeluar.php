<?php

namespace App\Filament\Resources\KeluarResource\Pages;

use App\Filament\Resources\KeluarResource;
use App\Models\Keluar;
use App\Models\Revision;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;

class ViewKeluar extends ViewRecord
{
    protected static string $resource = KeluarResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string | Htmlable
    {
        return __('Surat Keluar');
    }

    protected function getHeaderActions(): array
    {
        if (auth()->user()->hasRole('Direktur')) {
            if ($this->record->status == 0) {
                return [
                    Action::make('Revisi')->label('Revisi')->icon('heroicon-o-pencil')->color('danger')
                        ->form([
                            RichEditor::make('revisi')->label('Masukan Revisi')->required()
                        ])->action(
                            function (array $data, Keluar $record) {

                                try {
                                    Revision::create([
                                        'surat' =>  $record->id,
                                        'revisi' => $data['revisi'],
                                        'user' => auth()->user()->id
                                    ]);

                                    Keluar::where('id', $record->id)->update([
                                        'status' => 2
                                    ]);

                                    Notification::make()
                                        ->title('Berhasil dikirim!')
                                        ->icon('heroicon-o-pencil')
                                        ->iconColor('success')
                                        ->send();
                                } catch (\Throwable $th) {
                                    Notification::make()
                                        ->title($th->getMessage())
                                        ->icon('heroicon-o-information-circle')
                                        ->iconColor('danger')
                                        ->send();
                                }
                            }
                        ),
                    Action::make('Setuju')->label('Setujui')->icon('heroicon-o-check-circle')->color('success')
                        ->action(
                            function (array $data, Keluar $record) {
                                try {
                                    Keluar::where('id', $record->id)->update([
                                        'status' => 1
                                    ]);
                                    Notification::make()
                                        ->title('Berhasil disetujui!')
                                        ->icon('heroicon-o-pencil')
                                        ->iconColor('success')
                                        ->send();
                                } catch (\Throwable $th) {
                                    Notification::make()
                                        ->title($th->getMessage())
                                        ->icon('heroicon-o-information-circle')
                                        ->iconColor('danger')
                                        ->send();
                                }
                            }
                        )->requiresConfirmation()
                        ->color('success')
                        ->modalIcon('heroicon-o-check-circle')
                        ->modalIconColor('success'),
                    Action::make('Preview')->label('Preview')->icon('heroicon-o-eye')->color('warning'),
                ];
            } elseif ($this->record->status == 1) {
                return [
                    Action::make('download')->label('Download')->icon('heroicon-o-arrow-down-tray')->color('primary'),
                    Action::make('Batal')->label('Batal Setuju')->icon('heroicon-o-minus-circle')->color('danger')
                        ->action(
                            function (array $data, Keluar $record) {
                                try {
                                    Keluar::where('id', $record->id)->update([
                                        'status' => 0
                                    ]);
                                    Notification::make()
                                        ->title('Berhasil dibatalkan!')
                                        ->icon('heroicon-o-pencil')
                                        ->iconColor('success')
                                        ->send();
                                } catch (\Throwable $th) {
                                    Notification::make()
                                        ->title($th->getMessage())
                                        ->icon('heroicon-o-information-circle')
                                        ->iconColor('danger')
                                        ->send();
                                }
                            }
                        )->requiresConfirmation()
                        ->color('danger')
                        ->modalIcon('heroicon-o-minus-circle')
                        ->modalIconColor('danger'),
                ];
            } else {
                return [
                    Action::make('proses')->label('Proses')->icon('heroicon-o-information-circle')->color('warning')->disabled(),
                ];
            };
        } else {
            if ($this->record->status == 2) {
                return [
                    EditAction::make()->label('Edit')->icon('heroicon-o-pencil')->color('warning'),
                    Action::make('kirim')->label('Kirim')->icon('heroicon-o-check-circle')->color('success')
                    ->action(
                        function (array $data, Keluar $record) {
                            try {
                                Keluar::where('id', $record->id)->update([
                                    'status' => 0
                                ]);
                                Notification::make()
                                    ->title('Berhasil disetujui!')
                                    ->icon('heroicon-o-pencil')
                                    ->iconColor('success')
                                    ->send();
                            } catch (\Throwable $th) {
                                Notification::make()
                                    ->title($th->getMessage())
                                    ->icon('heroicon-o-information-circle')
                                    ->iconColor('danger')
                                    ->send();
                            }
                        }
                    )->requiresConfirmation()
                    ->color('success')
                    ->modalIcon('heroicon-o-check-circle')
                    ->modalIconColor('success'),
                    Action::make('Revisi')->label('Revisi')->icon('heroicon-o-information-circle')->color('danger')->disabled(),
                ];
            } elseif ($this->record->status == 0) {
                return [
                    EditAction::make()->label('Edit')->icon('heroicon-o-pencil')->color('warning'),
                    Action::make('Proses')->label('Proses')->icon('heroicon-o-ellipsis-horizontal-circle')->color('warning')->disabled(),
                ];
            } else {
                return [
                    Action::make('Setujui')->label('Setujui')->icon('heroicon-o-check-circle')->color('success')->disabled(),
                ];
            }
        }
    }
}
