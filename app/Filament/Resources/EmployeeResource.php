<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                FileUpload::make('avatar')->directory('profil_pegawai')->avatar(),
                TextInput::make('name'),
                TextInput::make('email'),
                TextInput::make('phone'),
                TextInput::make('nip'),
                TextInput::make('nik'),
                Textarea::make('address'),
                Select::make('gender')->label('jenis kelamin')->options([
                    'Laki-Laki' => 'Laki-Laki',
                    'Perempuan' => 'Perempuan',
                ]),
                Select::make('status')->options([
                    'PNS' => 'PNS',
                    'PPPK' => 'PPPK',
                    'Non-ASN' => 'Non-ASN',
                ])->live()->afterStateUpdated(fn(Set $set) => $set('grade', null)),
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
                })->label('Golongan'),
                TextInput::make('position')->label('Jabatan'),
                DatePicker::make('date_of_birth')->label('Tanggal Lahir')->native(false),
                DatePicker::make('date_of_joining')->label('Tanggal Bergabung')->native(false),
                FileUpload::make('file')->directory('file-pegawai')->getUploadedFileNameForStorageUsing(
                    fn(TemporaryUploadedFile $file, Get $get): string => (string)   $get('nip') . '_' . $get('name') . '_' . auth()->user()->kua()->first()->id_kua . str($file->getClientOriginalName())->prepend('-'),
                )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Jadikan User')->hidden(fn(): bool => !auth()->user()->isSuperAdmin())
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
