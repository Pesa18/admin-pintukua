<?php

namespace App\Filament\Resources\CompanieResource\Pages;

use App\Filament\Resources\CompanieResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCompanie extends CreateRecord
{
    protected static string $resource = CompanieResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        return $data;
    }
}
