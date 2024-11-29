<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Article;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\View\View;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use App\Forms\Components\ContentEditor;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ArticleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArticleResource\RelationManagers;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $tenantRelationshipName = 'articleTeams';

    public static function getEloquentQuery(): Builder
    {
        $id_kua = auth()->user()->kua()->first()?->id_kua;

        if (auth()->user()->isSuperAdmin() || auth()->user()->isEditor() || auth()->user()->isAdmin()) {
            return parent::getEloquentQuery()->withoutGlobalScopes();
        }

        return parent::getEloquentQuery()->whereHas('kua', function ($query) use ($id_kua) {
            $query->where('kua_user.id_kua', $id_kua);
        });
    }

    public static function isScopedToTenant(): bool
    {
        if (auth()->user()->isSuperAdmin() || auth()->user()->isEditor() || auth()->user()->isAdmin()) {
            return false;
        }
        return true;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Artikel')->columns(2)->schema([
                        TextInput::make('title')->live(debounce: 700)->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state)))->required(),
                        TextInput::make('slug')->required()->readOnly(),
                        ContentEditor::make(name: 'content')->label('content')->id(1)->columnSpanFull()->required()
                    ]),
                ])->columns(2)->columnSpan(2),
                Group::make()->schema([
                    Section::make('image')->schema([
                        FileUpload::make('image')
                            ->image()
                            ->imageEditor()->image()->directory('article')->required(),
                    ]),

                    Section::make('Categorie & Tags')->schema([
                        Select::make('category_id')->relationship(name: 'categories', titleAttribute: 'name')->native(false)->required(),
                        CheckboxList::make('tags')->relationship('tags', 'name')->columns(2)->gridDirection('row'),
                    ]),
                ])->columns(2),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('No.')
                    ->rowIndex(),
                TextColumn::make('title')->words(3),
                TextColumn::make('slug')->limit(10)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();

                        if (strlen($state) <= $column->getCharacterLimit()) {
                            return null;
                        }

                        // Only render the tooltip if the column content exceeds the length limit.
                        return $state;
                    })->searchable(),
                TextColumn::make('status')->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),
                TextColumn::make('published_at')->default('-'),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('team.name'),
                TextColumn::make('kua.name')->label('KUA')->default('Bukan User KUA'),
                TextColumn::make('viewers')->state(function ($record) {
                    return  Article::withoutGlobalScopes()->withCount('viewers')->find($record->uuid)?->viewers_count;
                }),
                ImageColumn::make('image'),

            ])
            ->filters([
                Filter::make('is_published')
                    ->query(fn(Builder $query): Builder => $query->whereNot('published_at', true, null))->toggle(),
                SelectFilter::make('KUA')
                    ->relationship('kua', 'name')
                    ->searchable()->visible(auth()->user()->isSuperAdmin() || auth()->user()->isEditor() || auth()->user()->isAdmin())
            ])
            ->modifyQueryUsing(function (Builder $query) {
                // $query->where('team_id', getPermissionsTeamId());

                // dd(getPermissionsTeamId());
            })
            ->actions([
                Tables\Actions\Action::make('Publish')->label("Publish")
                    ->button()->hidden(fn(Article $record) => $record->published_at)
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()->modalIcon('heroicon-o-arrow-path')
                    ->action(fn(Article $record) => $record->update(['published_at' => Carbon::now(), 'status' => 'published']))->visible(auth()->user()->isSuperAdmin() || auth()->user()->isEditor() || auth()->user()->isAdmin()),
                Tables\Actions\Action::make('Draft')->label("Draft")
                    ->button()->hidden(fn(Article $record) => !$record->published_at)
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()->modalIcon('heroicon-o-arrow-path')
                    ->action(fn(Article $record) => $record->update(['published_at' => null, 'status' => 'draft']))->visible(auth()->user()->isSuperAdmin() || auth()->user()->isEditor() || auth()->user()->isAdmin()),
                Tables\Actions\Action::make('View')->modalContent(fn(Article $record): View => view(
                    'filament.pages.actions.view-article',
                    ['record' => $record],
                ))->modalSubmitAction(false)->modalCancelAction(false),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
