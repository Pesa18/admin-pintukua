<?php

namespace App\Filament\Resources\AppbannerResource\Pages;

use App\Filament\Resources\AppbannerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppbanner extends EditRecord
{
    protected static string $resource = AppbannerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
