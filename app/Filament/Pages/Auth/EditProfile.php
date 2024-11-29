<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Profile')->tabs([
                    Tab::make('User')->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),
                    Tab::make('Pegawai')->schema([
                        Group::make()->schema([
                            TextInput::make('kua.id')->hidden(),
                            FileUpload::make('kua.avatar')->avatar()->columnSpanFull()->alignCenter(),
                            TextInput::make('kua.name')->rules('required')->markAsRequired(true)->validationMessages([
                                'required' => 'Nama Tidak Boleh Kosong',
                            ]),
                            TextInput::make('kua.email'),
                            TextInput::make('kua.phone'),
                            TextInput::make('kua.nik'),
                            TextInput::make('kua.nip'),
                            Textarea::make('kua.address'),
                            Select::make('kua.gender')->label('jenis kelamin')->options([
                                'Laki-Laki' => 'Laki-Laki',
                                'Perempuan' => 'Perempuan',
                            ]),
                            Select::make('kua.status')->options([
                                'PNS' => 'PNS',
                                'PPPK' => 'PPPK',
                                'Non-ASN' => 'Non-ASN',
                            ])->live()->afterStateUpdated(fn(Set $set) => $set('kua.grade', null)),
                            Select::make('kua.grade')->label('golongan')->options(
                                function (Get $get) {
                                    $is_PPPK = $get('kua.status');
                                    if ($is_PPPK === 'PPPK') {
                                        // Mengambil data kota dari API berdasarkan provinsi yang dipilih
                                        return [
                                            'I' => 'I',
                                            'II' => 'II',
                                            'III' => 'III',
                                            'IV' => 'IV',
                                            'V' => 'V',
                                            'VI' => 'VI',
                                            'VII' => 'VII',
                                            'VIII' => 'VIII',
                                            'IX' => 'IX',
                                            'X' => 'X',
                                            'XI' => 'XI',
                                            'XII' => 'XII',
                                            'XIII' => 'XIII',
                                            'XIV' => 'XIV',
                                            'XV' => 'XV',
                                            'XVI' => 'XVI',
                                            'XVII' => 'XVII',
                                        ];
                                    }

                                    return [
                                        'Ia' => 'Ia/Juru Muda',
                                        'Ib' => 'Ib/Juru Muda Tingkat I',
                                        'Ic' => 'Ic/Juru',
                                        'Id' => 'Id/Juru Tingkat I',
                                        'IIa' => 'IIa/Pengatur Muda',
                                        'IIb' => 'IIb/Pengatur Muda Tingkat I',
                                        'IIc' => 'IIc/Pengatur',
                                        'IId' => 'IId/Pengatur Tingkat I',
                                        'IIIa' => 'IIIa/Penata Muda',
                                        'IIIb' => 'IIIb/Penata Muda Tingkat 1',
                                        'IIIc' => 'IIIc/Penata',
                                        'IIId' => 'IIId/Penata Tingkat I',
                                        'IVa' => 'IVa/Pembina',
                                        'IVb' => 'IVb/Pembina Tingkat I',
                                        'IVc' => 'IV/cPembina Muda',
                                        'IVd' => 'IVd/Pembina Madya',
                                        'IVe' => 'IVe/Pembina Utama',

                                    ];
                                }

                            )->disabled(function (Get $get) {
                                $is_PPPK = $get('kua.status');
                                return $is_PPPK === 'Non-ASN';
                            })->label('Golongan'),
                            TextInput::make('kua.position')->label('Jabatan'),
                            DatePicker::make('kua.date_of_birth')->label('Tanggal Lahir')->native(false),
                            DatePicker::make('kua.date_of_joining')->label('Tanggal Bergabung')->native(false),
                        ])->columns(2)

                    ])->visible($this->getUser()->is_pegawai()->first()?->id ?? false)
                ])
            ]);
    }



    protected function fillForm(): void
    {

        $data = $this->getUser()->attributesToArray();
        $data['kua'] = $this->getUser()->is_pegawai()->first()?->toArray();

        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill($data);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $is_pegawai = $data['kua'] ?? null;

        if ($is_pegawai) {
            $record->is_pegawai()->update($is_pegawai);
            unset($data['kua']);
        }

        $record->update($data);

        return $record;
    }
    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => $this->hasTopbar(),
            'maxWidth' => MaxWidth::FiveExtraLarge,
        ];
    }
}
