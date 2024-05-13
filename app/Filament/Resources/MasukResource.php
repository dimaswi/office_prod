<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MasukResource\Pages;
use App\Filament\Resources\MasukResource\RelationManagers;
use App\Models\JenisSurat;
use App\Models\Masuk;
use App\Models\Surat;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions\Action as ComponentsActionsAction;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MasukResource extends Resource
{
    protected static ?string $model = Masuk::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationLabel = 'Surat Masuk';

    protected static ?string $navigationGroup = 'Surat';

    public static function form(Form $form): Form
    {
        $jenis_surat = Surat::where('nama_surat', 'Surat Masuk')->first();

        return $form
            ->schema([
                Wizard::make([
                    Step::make('Informasi Surat')->schema([
                        Hidden::make('jenis_surat')->default($jenis_surat->id),
                        Hidden::make('unit')->default(auth()->user()->unit),
                        TextInput::make('nomor_surat')->required()->placeholder('Masukan Nomor Surat')->columnSpanFull(),
                        DatePicker::make('tanggal_surat')->required()->placeholder('Masukan Tanggal Surat'),
                        DatePicker::make('tanggal_diterima')->required()->placeholder('Masukan Tanggal Diterima Surat'),
                    ])->columns(2),
                    Step::make('Surat')->schema([
                        Select::make('sifat_surat')->required()->options([
                            'Biasa' => 'Biasa',
                            'Segera' => 'Segera',
                            'Penting' => 'Penting',
                        ]),
                        TextInput::make('lampiran')->required()->placeholder('Masukan Lampiran Surat'),
                        TextInput::make('perihal_surat')->required()->placeholder('Masukan Perihal Surat'),
                        TextInput::make('pengirim_surat')->required()->placeholder('Masukan Pengirim Surat'),
                        RichEditor::make('isi_surat')->required()->columnSpanFull(),
                        FileUpload::make('dokumen_surat')->required()
                            ->directory('surat-masuk')
                            ->storeFileNamesIn('original_filename')
                            ->downloadable()
                            ->columnSpanFull(),
                    ])->columns(2),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_surat')->badge()->searchable()->sortable(),
                TextColumn::make('pengirim_surat')->searchable()->sortable(),
                TextColumn::make('sifat_surat')->badge()->searchable()->sortable()
                    ->color(fn (string $state): string => match ($state) {
                        'Segera' => 'warning',
                        'Biasa' => 'success',
                        'Penting' => 'danger',
                    }),
                TextColumn::make('lampiran')->badge()->searchable()->sortable(),
                TextColumn::make('tanggal_surat')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                ActionsAction::make('disposisi')->infolist([
                    RepeatableEntry::make('tindak_lanjuts')->label('')
                        ->schema([
                            Section::make('Tindak Lanjut')
                                ->headerActions([
                                    ComponentsActionsAction::make('Download')
                                        ->action(function () {
                                            // ...
                                        }),
                                ])
                                ->schema([
                                    TextEntry::make('user.name')->label('Nama')->badge(),
                                    TextEntry::make('created_at')->label('Waktu')->since()->badge(),
                                    TextEntry::make('catatan')->label('Catatan')->html()->columnSpanFull(),
                                ])->columns(2)
                        ])
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('nomor_surat')->badge(),
                        TextEntry::make('sifat_surat')->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Segera' => 'warning',
                                'Biasa' => 'success',
                                'Penting' => 'danger',
                            }),
                        TextEntry::make('tanggal_surat'),
                        TextEntry::make('tanggal_diterima'),
                        TextEntry::make('pengirim_surat'),
                        TextEntry::make('lampiran')->badge(),
                        TextEntry::make('perihal_surat')->columnSpanFull(),
                        TextEntry::make('isi_surat')->html()->columnSpanFull(),
                    ])->columns(2)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMasuks::route('/'),
            'create' => Pages\CreateMasuk::route('/create'),
            'edit' => Pages\EditMasuk::route('/{record}/edit'),
            'view' => Pages\ViewMasuk::route('/{record}'),
        ];
    }
}
