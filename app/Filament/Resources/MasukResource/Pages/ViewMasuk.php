<?php

namespace App\Filament\Resources\MasukResource\Pages;

use App\Filament\Resources\MasukResource;
use App\Models\Masuk;
use App\Models\TLMasuk;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Storage;

class ViewMasuk extends ViewRecord
{
    protected static string $resource = MasukResource::class;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getTitle(): string | Htmlable
    {
        return __($this->record->perihal_surat);
    }

    protected function getHeaderActions(): array
    {
        $check_disposisi = Masuk::where('id', $this->record->id)->where('disposisi', 'LIKE', "%" . auth()->user()->name . "%")->first();

        if (!empty($check_disposisi)) {
            return [
                Action::make('Download')->icon('heroicon-o-arrow-down-tray')->url(Storage::url($this->record->dokumen_surat)),
                Action::make('Tindak Lanjut')->icon('heroicon-o-forward')->color('success')
                    ->form([
                        RichEditor::make('catatan')->required(),
                        Select::make('disposisi')->searchable()->multiple()->options(User::all()->pluck('name', 'name'))
                    ])
                    ->action(
                        function (array $data, Masuk $record): void {
                            $surat_masuk = Masuk::find($record->id);
                            // dd(in_array('Haryono',json_decode($surat_masuk->disposisi)));
                            // dd($data['catatan']);

                            foreach ($data['disposisi'] as $key => $value) {
                                if (in_array($value, json_decode($surat_masuk->disposisi)) == true) {
                                } else {
                                    $array = json_decode($surat_masuk->disposisi);
                                    // $new_disposisi = array_push($array,$value);
                                    $array[] = $value;
                                    $surat_masuk->disposisi = json_encode($array);
                                    $surat_masuk->save();
                                    // dd($surat_masuk->disposisi);

                                    $user_notify = User::where('name', $value)->first();
                                    $user_notify->notify(
                                        Notification::make()
                                            ->title('Surat Masuk diterima!')
                                            ->toDatabase(),
                                    );
                                }
                            }

                            $TLMasuk = new TLMasuk();
                            $TLMasuk->surat_masuk_id = $surat_masuk->id;
                            $TLMasuk->catatan = $data['catatan'];
                            $TLMasuk->user_id = auth()->user()->id;
                            $TLMasuk->save();

                            Notification::make()
                                ->title('Berhasil ditindak lanjutkan!')
                                ->icon('heroicon-o-document-text')
                                ->iconColor('success')
                                ->send();
                        }
                    ),
            ];
        } else {
            return [
                Action::make('Disposisi')->icon('heroicon-o-share')
                    ->color('success')
                    ->form([
                        Select::make('disposisi')->options(User::all()->pluck('name', 'name'))->multiple()->searchable(),
                    ])->action(function (array $data, Masuk $record): void {

                        foreach ($data['disposisi'] as $key => $value) {
                            $user_notify = User::where('name', $value)->first();
                            $user_notify->notify(
                                Notification::make()
                                    ->title('Surat Masuk diterima!')
                                    ->toDatabase(),
                            );
                        }

                        $record->disposisi = $data['disposisi'];
                        $record->save();
                    }),
                EditAction::make()->icon('heroicon-o-pencil-square')->color('warning'),
                Action::make('Download')->icon('heroicon-o-arrow-down-tray')->url(Storage::url($this->record->dokumen_surat))
            ];
        }
    }
}
