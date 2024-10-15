<?php

namespace App\Filament\Resources\UserkuaResource\Pages;

use App\Filament\Resources\UserkuaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserkuas extends ListRecords
{
    protected static string $resource = UserkuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
