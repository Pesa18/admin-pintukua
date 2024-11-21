<?php

namespace App\Filament\Resources\UserAdminResource\Pages;

use Filament\Actions;
use App\Models\KuaTeam;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\UserAdminResource;

class EditUserAdmin extends EditRecord
{
    protected static string $resource = UserAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        if (isset($data['id_kua']) && $data['id_kua']) {
            $update =  KuaTeam::where('user_id', $record->id)->update([
                'id_kua' => $data['id_kua'],
                'user_id' => $record->id,
            ]);

            if (!$update) {
                KuaTeam::create([
                    'id_kua' => $data['id_kua'],
                    'user_id' =>  $record->id,
                ]);
            }
        }
        $record->update($data);

        return $record;
    }
}
