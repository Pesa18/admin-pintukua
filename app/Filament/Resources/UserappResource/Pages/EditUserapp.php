<?php

namespace App\Filament\Resources\UserappResource\Pages;

use App\Filament\Resources\UserappResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserapp extends EditRecord
{
    protected static string $resource = UserappResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
