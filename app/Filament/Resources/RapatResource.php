<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RapatResource\Pages;
use App\Filament\Resources\RapatResource\RelationManagers;
use App\Models\Rapat;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RapatResource extends Resource
{
    protected static ?string $model = Rapat::class;

    protected static ?string $navigationLabel = 'Rapat';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $data_rapat = Rapat::orderBy('id', 'desc')->first();
        $data_rapat_unit = Rapat::where('unit_id', auth()->user()->unit)->orderBy('id', 'desc')->first();
        $data_rapat_unit_all = Rapat::where('unit_id', auth()->user()->unit)->orderBy('id', 'desc')->get();

        if (empty($data_rapat)) {
            $nomor = 1;
        } else if (empty($data_rapat_unit)) {
            $nomor = 1;
        } else if ($data_rapat_unit_all->count() > 0) {
            $nomor = $data_rapat_unit->nomor + 1;
        }

        $unit = Unit::where('id', auth()->user()->unit)->first();

        return $form
            ->schema([
                Card::make()->schema([
                    Hidden::make('nomor')->default($nomor),
                    TextInput::make('nomor_rapat')
                        ->readOnly()
                        ->default(str_pad($nomor, 2, '0', STR_PAD_LEFT) . '/' . $unit->kode_unit . '/' . Carbon::now('Asia/Jakarta')->format('m') . '/' . Carbon::now('Asia/Jakarta')->format('Y'))
                        ->required()
                        ->columnSpan(2),
                    Select::make('user_id')->label('Pimpinan Rapat')
                        ->relationship('pimpinan')
                        ->required()
                        ->preload()
                        ->options(
                            User::all()->pluck('name', 'id')
                        )
                        ->searchable(),
                    TextInput::make('agenda_rapat')
                        ->placeholder('Masukan Agenda Rapat')
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('tempat_rapat')
                        ->placeholder('Masukan Lokasi Rapat')
                        ->required(),
                    DatePicker::make('tanggal_rapat')
                        ->required()
                        ->columnSpan(2),
                    Select::make('hari_rapat')
                        ->required()
                        ->options([
                            'Senin' => 'Senin',
                            'Selasa' => 'Selasa',
                            'Rabu' => 'Rabu',
                            'Kamis' => 'Kamis',
                            'Jumat' => 'Jumat',
                            'Sabtu' => 'Sabtu',
                            'Minggu' => 'Minggu',
                        ]),
                    TimePicker::make('jam_rapat')
                        ->required(),
                    DateTimePicker::make('starts_at')
                        ->label('Mulai Rapat')
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Selesai Rapat')
                        ->required(),
                    Select::make('users')->label('Undangan')
                        ->relationship('users', 'name')
                        ->multiple()
                        ->preload()
                        ->options(
                            User::all()->pluck('name', 'id')
                        )
                        ->columnSpanFull(),
                ])->columns(3)
            ]);
    }

    // public static function table(Table $table): Table
    // {
    //     return $table
    //         ->columns([
    //             TextColumn::make('nomor_rapat')->searchable()->sortable()->badge(),
    //             TextColumn::make('unit.nama_unit')->searchable()->sortable(),
    //             TextColumn::make('agenda_rapat')->searchable()->sortable(),
    //             // TextColumn::make('tempat_rapat')->searchable()->sortable(),
    //             TextColumn::make('tanggal_rapat')->searchable()->sortable()->date(),
    //             // TextColumn::make('users.name')->badge()->label('Undangan'),
    //             TextColumn::make('created_at')->badge()->label('Dibuat')->since(),
    //         ])
    //         ->filters([
    //             //
    //         ])
    //         ->actions([
    //             // Tables\Actions\EditAction::make(),
    //         ])
    //         ->bulkActions([
    //             Tables\Actions\BulkActionGroup::make([
    //                 Tables\Actions\DeleteBulkAction::make(),
    //             ]),
    //         ]);
    // }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()
                ->schema([
                    TextEntry::make('nomor_rapat')->label('Nomor Rapat')->badge(),
                    TextEntry::make('jam_rapat')->label('Jam')->badge(),
                    TextEntry::make('hari_rapat')->label('Hari Rapat'),
                    TextEntry::make('tanggal_rapat')->label('Tanggal Rapat'),
                    TextEntry::make('starts_at')->label('Jam Mulai')->badge(),
                    TextEntry::make('ends_at')->label('Jam Selesai')->badge(),
                    TextEntry::make('unit.nama_unit')->label('Unit Rapat'),
                    TextEntry::make('pimpinan.name')->label('Pimpinan Rapat'),
                    TextEntry::make('tempat_rapat')->label('Tempat Rapat'),
                    TextEntry::make('agenda_rapat')->label('Agenda Rapat'),
                ])->columns(2),

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
            'index' => Pages\ListRapats::route('/'),
            'create' => Pages\CreateRapat::route('/create'),
            'edit' => Pages\EditRapat::route('/{record}/edit'),
            'view' => Pages\View::route('/{record}'),
            'daftar_hadir' => Pages\DaftarHadirRapats::route('/{record}/daftar_hadir'),
            'notulen' => Pages\Notulen::route('{record}/notulen'),
        ];
    }
}
