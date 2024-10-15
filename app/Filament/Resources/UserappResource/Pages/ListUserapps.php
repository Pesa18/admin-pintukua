<?php

namespace App\Filament\Resources\UserappResource\Pages;

use App\Filament\Resources\UserappResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserapps extends ListRecords
{
    protected static string $resource = UserappResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
