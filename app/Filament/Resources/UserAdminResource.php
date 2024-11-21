<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\KuaTeam;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\ProfileCompany;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\UserAdminResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserAdminResource\RelationManagers;

class UserAdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $tenantRelationshipName = 'userTeams';
    protected static bool $isScopedToTenant = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->unique(column: 'email', ignoreRecord: true)->required()->email(),
                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->saveRelationshipsUsing(function (Model $record, $state) {
                        $record->roles()->syncWithPivotValues($state, [config('permission.column_names.team_foreign_key') => getPermissionsTeamId()]);
                    })
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                    ->dehydrated(fn(?string $state): bool => filled($state))
                    ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord),

                Forms\Components\Select::make('id_kua')->hidden(!auth()->user()->isTeamkua())->dehydrated(auth()->user()->isTeamkua())->rules('required')->markAsRequired(true)->validationMessages([
                    'required' => 'Nama Harus diisi!',
                ])->options(ProfileCompany::all()->pluck('name', 'id_kua'))->afterStateHydrated(function (Set $set, $record) {

                    return $set('id_kua', $record ? $record->kua()->first()?->id_kua : null);
                })
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('roles.name'),
                TextColumn::make('email'),
                TextColumn::make('kua.id_kua')->default('Bukan User KUA')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('id_kua')->form([
                    TextInput::make('id_kua')
                ])->action(function (array $data,  $record): void {
                    KuaTeam::create([
                        'id_kua' => $data['id_kua'],
                        'user_id' => $record->id,
                    ]);
                })
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
            'index' => Pages\ListUserAdmins::route('/'),
            'create' => Pages\CreateUserAdmin::route('/create'),
            'edit' => Pages\EditUserAdmin::route('/{record}/edit'),
        ];
    }
}
