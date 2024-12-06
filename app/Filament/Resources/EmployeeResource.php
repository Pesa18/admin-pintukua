<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\KuaTeam;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $label = 'Data Pegawai';
    protected static ?string $pluralLabel = 'Data Pegawai';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $tenantRelationshipName = 'employee';
    // protected static bool $isScopedToTenant = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id_kua', auth()->user()->kua()->first()?->id_kua);
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('avatar')->required()->label('Foto')->directory('profil_pegawai')->avatar()->columnSpanFull()->alignCenter()->image()->label('Foto')->rules('required')->markAsRequired(true)->validationMessages([
                    'required' => 'Foto Harus diisi!',
                ]),
                Section::make('Data Personal')->schema([
                    TextInput::make('name')->label('Nama Lengkap')->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Nama Harus diisi!',
                    ]),
                    TextInput::make('nik')->label('NIK')->numeric()->rules('required|numeric|min_digits:16')->markAsRequired(true)->validationMessages([
                        'required' => 'NIK Harus diisi!',
                        'numeric' => 'Harus Berupa Angka',
                        'min_digits' => 'NIK harus 16 Digits'
                    ]),
                    DatePicker::make('date_of_birth')->label('Tanggal Lahir')->native(false)->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Tanggal Lahir Harus diisi!',
                    ]),
                    TextInput::make('email')->email()->rules('required|email')->markAsRequired(true)->validationMessages([
                        'required' => 'Email Harus diisi!',
                        'email' => ' Harus Berupa Email',
                    ]),
                    TextInput::make('phone')->label('No. HP')->numeric()->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'No. HP Harus diisi!',
                    ]),
                    Textarea::make('address')->label('Alamat')->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Alamat Harus diisi!',
                    ]),
                    Select::make('gender')->label('jenis kelamin')->options([
                        'Laki-Laki' => 'Laki-Laki',
                        'Perempuan' => 'Perempuan',
                    ])->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Jenis Kelamin Harus diisi!',
                    ]),
                ])->columns(2),

                Section::make('Data Kepegawaian')->schema([
                    TextInput::make('nip')->label('NIP')->rules('required|numeric|min_digits:18')->markAsRequired(true)->validationMessages([
                        'required' => 'NIP Harus diisi!',
                        'numeric' => 'Harus Berupa Angka',
                        'min_digits' => 'NIP harus 18 Digits',
                        'unique' => 'NIP sudah ada'
                    ])->unique(column: 'nip', ignoreRecord: true),
                    Select::make('status')->options([
                        'PNS' => 'PNS',
                        'PPPK' => 'PPPK',
                        'Non-ASN' => 'Non-ASN',
                    ])->live()->afterStateUpdated(fn(Set $set) => $set('grade', null))->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Status Harus diisi!',

                    ]),
                    Select::make('grade')->label('golongan')->options(
                        function (Get $get) {
                            $is_PPPK = $get('status');
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
                        $is_PPPK = $get('status');
                        return $is_PPPK === 'Non-ASN';
                    })->label('Golongan')->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Golongan Harus diisi!',

                    ]),
                    TextInput::make('position')->label('Jabatan')->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Jabatan Harus diisi!',
                    ]),

                    DatePicker::make('date_of_joining')->label('Tanggal Bergabung')->native(false)->rules('required')->markAsRequired(true)->validationMessages([
                        'required' => 'Tanggal Bergabung Harus diisi!',

                    ]),
                ])->columns(2),

                FileUpload::make('file')->directory('file-pegawai')->getUploadedFileNameForStorageUsing(
                    fn(TemporaryUploadedFile $file, Get $get): string => (string)   $get('nip') . '_' . $get('name') . '_' . auth()->user()->kua()->first()->id_kua . str($file->getClientOriginalName())->prepend('-'),
                )->downloadable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')->label('Foto')
                    ->circular(),
                TextColumn::make('name')->label('Nama'),
                ToggleColumn::make('is_kepala')->label('Kepala KUA')
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            $record::where('id_kua', $record->id_kua) // Filter sesuai id_kua jika ada
                                ->where('id', '!=', $record->id)     // Kecualikan record yang sedang diubah
                                ->update(['is_kepala' => false]);
                        }
                    }),
                TextColumn::make('is_user.name')->label('User')->action(Action::make('open')->modal())->badge()->default('Bukan User'),
                ToggleColumn::make('is_active')->label('Pegawai Aktif')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\Action::make('Jadikan User')
                    ->action(function (array $data, User $user, KuaTeam $kua, Employee $record,) {
                        if ($data) {
                            $user_data = $user->create($data);
                            if ($user_data) {
                                $user_data->roles()->syncWithPivotValues($data['roles'], [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                                $kua->create([
                                    'id_kua' => $record->id_kua,
                                    'id_employee' => $record->id,
                                    'user_id' => $user_data->id,
                                ]);
                            }
                        }
                    })
                    ->form([
                        TextInput::make('name')->label('User Name')->rules('required')->markAsRequired(true)->validationMessages([
                            'required' => 'Nama Harus diisi!',
                        ]),
                        TextInput::make('email')->email()->rules('required|email')->markAsRequired(true)->validationMessages([
                            'required' => 'Nama Harus diisi!',
                            'email' => 'Harus berupa email'
                        ]),
                        TextInput::make('password')->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))->rules('required')->markAsRequired(true)->validationMessages([
                                'required' => 'Password Harus diisi!',
                            ]),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->dehydrateStateUsing(fn($state) => $state)
                            ->dehydrated(fn($state): bool => filled($state))
                            ->searchable()
                            ->model(User::class)->rules('required')->markAsRequired(true)->validationMessages([
                                'required' => 'Roles Harus diisi!',
                            ]),
                    ])->model(User::class)
                    ->fillForm(fn(Employee $record): array => [
                        'name' => $record->name,
                        'email' => $record->email,
                    ])
                    ->hidden(fn(): bool => auth()->user()->isSuperAdmin())->visible(fn(Model $record): bool => !$record->is_user()->exists())
                    ->button()->color('warning')->closeModalByClickingAway(false)


            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
