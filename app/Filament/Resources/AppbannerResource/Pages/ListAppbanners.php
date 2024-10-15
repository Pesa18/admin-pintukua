<?php

namespace App\Filament\Resources\AppbannerResource\Pages;

use App\Filament\Resources\AppbannerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppbanners extends ListRecords
{
    protected static string $resource = AppbannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
