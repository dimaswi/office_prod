<?php

namespace App\Filament\Resources\RapatResource\Pages;

use App\Filament\Resources\RapatResource;
use App\Models\Notulen as ModelsNotulen;
use App\Models\Rapat;
use App\Models\UndanganRapat;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Notulen extends Page implements HasForms, HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable;
    use InteractsWithForms;

    public ?array $data = [];

    protected static string $resource = RapatResource::class;

    protected static string $view = 'filament.resources.rapat-resource.pages.notulen';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->reset('data.notulen');
    }

    public function getTitle(): string|Htmlable
    {
        return 'Daftar Notulen';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('notulen')->required()->placeholder('Masukan Notulen')->default(''),
                    ])
            ])->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Simpan')
                ->label(__('Simpan'))
                ->submit('simpanNotulen'),
        ];
    }

    public function simpanNotulen(): void
    {
        try {
            $data = $this->form->getState();

            // dd($data['notulen']);

            ModelsNotulen::create([
                'rapat_id' => $this->record->id,
                'notulen' => $data['notulen'],
            ]);

            Notification::make()
                ->title('Berhasil Disimpan!')
                ->success()
                ->send();

            $this->reset('data.notulen');
        } catch (Halt $exception) {
            return;
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsNotulen::where('rapat_id', $this->record->id)
            )
            ->columns([
                TextColumn::make('rapat.agenda_rapat')->searchable()->sortable(),
                TextColumn::make('notulen')->searchable()->sortable()
            ])
            ->actions([
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (ModelsNotulen $record) => $record->delete())
            ]);
    }
}
