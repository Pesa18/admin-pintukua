<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $id_kua = auth()->user()->kua()->first()->id_kua;
        if ($id_kua) {
            $data['id_kua'] = $id_kua;
        }
        return $data;
    }
}
