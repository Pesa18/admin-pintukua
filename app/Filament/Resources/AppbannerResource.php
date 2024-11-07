<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\BannerApp;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\AppbannerResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AppbannerResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class AppbannerResource extends Resource
{
    protected static ?string $model = BannerApp::class;


    protected static ?string $navigationLabel = 'Banner App';
    protected static ?string $navigationGroup = 'APP';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static bool $isScopedToTenant = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title'),
                TextInput::make('description'),
                TextInput::make('link'),
                DateTimePicker::make('start_at'),
                DateTimePicker::make('end_at'),
                Checkbox::make('is_active'),
                FileUpload::make('image_path')->image()
                    ->imageEditor()->image()->directory('banner-info')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('is_active')->badge()->color(fn(string $state): string => match ($state) {
                    '1' => 'success'
                })

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAppbanners::route('/'),
            'create' => Pages\CreateAppbanner::route('/create'),
            'edit' => Pages\EditAppbanner::route('/{record}/edit'),
        ];
    }
}
