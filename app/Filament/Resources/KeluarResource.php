<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeluarResource\Pages;
use App\Filament\Resources\KeluarResource\RelationManagers;
use App\Models\Keluar;
use App\Models\Surat;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeluarResource extends Resource
{
    protected static ?string $model = Keluar::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Surat Keluar';

    protected static ?string $navigationGroup = 'Surat';

    public static function form(Form $form): Form
    {
        $last_surat = Keluar::where('unit', auth()->user()->unit)->latest()->first();

        if (empty($last_surat)) {
            $nomor = 1;
        } else if (!empty($last_surat)) {

            if (Carbon::now('Asia/Jakarta')->format('m') != Carbon::parse($last_surat->tanggal_surat)->format('m')) {
                $nomor = 1;
            } else {
                $nomor = $last_surat->nomor + 1;
            }
        }

        $jenis_surat = Surat::where('nama_surat', 'Surat Keluar')->first();

        $nomor_surat = str_pad($nomor,  3, 0, STR_PAD_LEFT) . '/A/I/' . Carbon::now('Asia/Jakarta')->format('m') . '/' . Carbon::now('Asia/Jakarta')->format('Y');

        return $form
            ->schema([
                Wizard::make([
                    Step::make('Nomor Surat')->schema([
                        Hidden::make('nomor')->default($nomor),
                        Hidden::make('pembuat')->default(auth()->user()->id),
                        TextInput::make('nomor_surat')->default($nomor_surat)->columnSpanFull(),
                        TextInput::make('perihal_surat')->required()->placeholder('Masukan Perihal Surat'),
                        TextInput::make('penerima_surat')->required()->placeholder('Masukan Penerima Surat'),
                        Select::make('sifat_surat')->required()->options([
                            'Penting' => 'Penting',
                            'Segera' => 'Segera',
                            'Biasa' => 'Biasa',
                        ]),
                        DatePicker::make('tanggal_surat')->required(),
                        Hidden::make('unit')->default(auth()->user()->unit)->required(),
                        Hidden::make('jenis_surat')->default($jenis_surat->id)->required(),
                    ])->columns(2),
                    Step::make('Isi Surat')->schema([
                        RichEditor::make('isi_surat')->required()->columnSpanFull(),
                        TextInput::make('jabatan')->placeholder('Masukan Jabatan Pembuat Surat')->required(),
                        Select::make('tanda_tangan')->options(User::all()->pluck('name', 'id'))->required()->searchable(),
                    ])->columns(2),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_surat')->searchable()->sortable()->badge(),
                TextColumn::make('perihal_surat')->searchable()->sortable()->limit(100),
                TextColumn::make('penerima_surat')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('revisi')->icon('heroicon-o-clipboard-document-list')
                ->infolist([
                    RepeatableEntry::make('Revisi')->label('')
                    ->schema([
                        Section::make('Revisi')
                        ->schema([
                            TextEntry::make('SuratKeluar.nomor_surat')->label('Nomor Surat')->badge(),
                            TextEntry::make('User.name')->label('Nama'),
                            TextEntry::make('revisi')->label('Catatan')->html()->columnSpanFull(),
                        ])->columns(2)
                    ])
                ])
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
                ComponentsSection::make()
                    ->schema([
                        TextEntry::make('nomor_surat')->badge(),
                        TextEntry::make('sifat_surat')->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Segera' => 'warning',
                                'Biasa' => 'success',
                                'Penting' => 'danger',
                            }),
                        TextEntry::make('tanggal_surat'),
                        TextEntry::make('unit.nama_unit'),
                        TextEntry::make('perihal_surat'),
                        TextEntry::make('penerima_surat'),
                        TextEntry::make('isi_surat')->html()->columnSpanFull(),
                        TextEntry::make('jabatan'),
                        TextEntry::make('Tandatangan.name'),
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
            'index' => Pages\ListKeluars::route('/'),
            'create' => Pages\CreateKeluar::route('/create'),
            'edit' => Pages\EditKeluar::route('/{record}/edit'),
            'view' => Pages\ViewKeluar::route('/{record}/view'),
        ];
    }
}
