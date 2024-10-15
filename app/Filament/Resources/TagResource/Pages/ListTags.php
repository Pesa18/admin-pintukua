<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Models\Tag;
use Filament\Actions;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\TagResource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('New Tags')->form([
                Repeater::make('tags')
                    ->schema([
                        TextInput::make('name')->required()->live(debounce: 700)->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')->required()->readOnly(),
                    ])
                    ->columns(2)->addActionLabel('Add Tags')
            ])->action(function (array $data): void {
                try {
                    Tag::insert($data['tags']);
                    Notification::make()
                        ->title(count($data['tags']) . ' Data Berhasil Disimpan')
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
