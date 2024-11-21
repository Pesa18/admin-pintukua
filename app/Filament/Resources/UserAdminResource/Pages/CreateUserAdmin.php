<?php

namespace App\Filament\Resources\UserAdminResource\Pages;

use Filament\Actions;
use App\Models\KuaTeam;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserAdminResource;

class CreateUserAdmin extends CreateRecord
{
    protected static string $resource = UserAdminResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if (isset($data['id_kua']) && $data['id_kua']) {
            KuaTeam::create([
                'id_kua' => $data['id_kua'],
                'user_id' => $data['id'],
            ]);

            return $data;
        }
        return $data;
    }
}
