<?php

namespace App\Filament\Resources\UserkuaResource\Pages;

use App\Filament\Resources\UserkuaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserkua extends EditRecord
{
    protected static string $resource = UserkuaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
