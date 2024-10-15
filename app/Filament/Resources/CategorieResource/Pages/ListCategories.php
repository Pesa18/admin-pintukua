<?php

namespace App\Filament\Resources\CategorieResource\Pages;

use Filament\Actions;
use Filament\Forms\Set;
use App\Models\Categorie;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CategorieResource;

class ListCategories extends ListRecords
{
    protected static string $resource = CategorieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('New Categorie')->form([
                Repeater::make('categories')
                    ->schema([
                        TextInput::make('name')->required()->live(debounce: 700)->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->readOnly(),
                    ])
                    ->columns(2)->addActionLabel('Add Categorie')
            ])->action(function (array $data): void {
                try {
                    Categorie::insert($data['categories']);
                    Notification::make()
                        ->title(count($data['categories']) . ' Data Berhasil Disimpan')
                        ->success()
                        ->send();
                } catch (\Throwable $th) {
                    Notification::make()
                        ->title($th->getMessage())
                        ->danger()
                        ->send();
                }
            }),
        ];
    }
}
