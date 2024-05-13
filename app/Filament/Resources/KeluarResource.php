<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeluarResource\Pages;
use App\Filament\Resources\KeluarResource\RelationManagers;
use App\Models\Keluar;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
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

        $nomor_surat = str_pad($nomor,  3, 0, STR_PAD_LEFT) . '/A/I/' . Carbon::now('Asia/Jakarta')->format('m') . '/' . Carbon::now('Asia/Jakarta')->format('Y');

        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('nomor_surat')->default($nomor_surat),
                    Hidden::make('nomor')->default($nomor),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
        ];
    }
}
