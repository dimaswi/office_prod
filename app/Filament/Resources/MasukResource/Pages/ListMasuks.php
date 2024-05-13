<?php

namespace App\Filament\Resources\MasukResource\Pages;

use App\Filament\Resources\MasukResource;
use App\Models\Masuk;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListMasuks extends ListRecords
{
    protected static string $resource = MasukResource::class;

    public function getTabs(): array
    {
        $surat_baru = Masuk::where('disposisi', null)->count();
        $surat_lama = Masuk::where('disposisi', '!=', null)->count();
        // dd(Masuk::where('disposisi', '!=', null)->whereIn('disposisi', array(auth()->user()->id))->count());
        $surat_user = Masuk::where('disposisi', 'LIKE' , "%".auth()->user()->name."%")->count();

        // dd(auth()->user()->id);


        if (auth()->user()->hasRole('Admin')) {
            return [
                'baru' => Tab::make('Baru')
                ->badge($surat_baru)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('disposisi', null)),
                'disposisi' => Tab::make('Disposisi')
                ->badge($surat_lama)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('disposisi', '!=', null)),
            ];
        } else {
            return [
                'surat' => Tab::make('Surat')
                ->badge($surat_user)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('disposisi', 'LIKE' , "%".auth()->user()->name."%")),
            ];
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
