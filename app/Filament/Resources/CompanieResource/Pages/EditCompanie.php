<?php

namespace App\Filament\Resources\CompanieResource\Pages;

use App\Filament\Resources\CompanieResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompanie extends EditRecord
{
    protected static string $resource = CompanieResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
