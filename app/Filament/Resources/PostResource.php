<?php

namespace App\Filament\Resources;

use App\Adm\Actions\ActionAdmTranslationMapper;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\Widgets\PostStatsOverview;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    protected static function getNavigationLabel(): string
    {
        return trans('adm/dashboard.posts');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([

                    Card::make()
                        ->schema([
                            TextInput::make('title')
                                ->label(trans('adm/form.title'))
                                ->required()
                                ->lazy()
                                ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                ->columnSpanFull(),

                            TextInput::make('slug')
                                ->label(trans('adm/form.slug'))
                                ->required()
                                ->unique(self::getModel(), 'slug', ignoreRecord: true)
                                ->columnSpanFull(),

                            TinyEditor::make('short')
                                ->label(trans('adm/form.short'))
                                ->fileAttachmentsDisk('local')
                                ->fileAttachmentsVisibility('storage')
                                ->fileAttachmentsDirectory('public/uploads')
                                ->setConvertUrls(false)
                        ]),

                    Section::make('Content')
                        ->schema([
                            TinyEditor::make('content')
                                ->label(trans('adm/form.content'))
                                ->fileAttachmentsDisk('local')
                                ->fileAttachmentsVisibility('storage')
                                ->fileAttachmentsDirectory('public/uploads')
                                ->setConvertUrls(false)
                        ]),

                    Tabs::make('Heading')
                        ->tabs([
                            Tab::make('Images')
                                ->icon('heroicon-o-film')
                                ->schema([
                                    SpatieMediaLibraryFileUpload::make('media')
                                        ->collection('images')
                                        ->multiple()
                                        ->disableLabel(),
                                ])  ,
                            Tab::make('SEO')
                                ->icon('heroicon-o-folder')
                                ->schema([
                                    TextInput::make('seo_title')
                                        ->label(trans('adm/form.seo_title'))
                                        ->columnSpan('full'),
                                    Textarea::make('seo_text_keys')
                                        ->label(trans('adm/form.seo_text_keys'))
                                        ->columnSpan('full'),
                                    Textarea::make('seo_description')
                                        ->label(trans('adm/form.seo_text_keys'))
                                        ->columnSpan('full'),
                                ]),
                        ]),

                ])->columnSpan(3),

                Group::make()->schema([

                    Section::make('Taxonomy')
                        ->schema([

                            Select::make('type')
                                ->label(trans('adm/form.post_type'))
                                ->options(admPostTypes())
                                ->default('post')
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('categories', null)),

                            Select::make('categories')
                                ->label(trans('adm/form.categories'))
                                ->multiple()
                                ->preload()
                                ->relationship('categories', 'title')
                                ->createOptionForm([
                                    TextInput::make('title')
                                        ->label(trans('adm/form.title'))
                                        ->required()
                                        ->lazy()
                                        ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->label(trans('adm/form.slug'))
                                        ->required()
                                        ->unique(Category::class, 'slug', ignoreRecord: true)->columnSpanFull(),

                                    Select::make('post_type')
                                        ->label(trans('adm/form.post_type'))
                                        ->options(admPostTypes())
                                        ->default('post')
                                        ->required()
                                        ->reactive()
                                        ->afterStateUpdated(fn (callable $set) => $set('parent_id', null)),

                                    Select::make('parent_id')
                                        ->label(trans('adm/form.parent'))
                                        ->options(function(callable $get) {
                                            return Category::query()
                                                ->where('post_type', $get('post_type'))
                                                ->pluck('title', 'id');
                                        }),

                                    Select::make('lang')
                                        ->label(trans('adm/form.lang'))
                                        ->options(
                                            admLanguages()
                                        )
                                        ->default(admDefaultLanguage()),
                                ]),

                            Select::make('tags')
                                ->label(trans('adm/form.tags'))
                                ->multiple()
                                ->preload()
                                ->relationship('tags', 'title')
                                ->createOptionForm([
                                    TextInput::make('title')
                                        ->label(trans('adm/form.title'))
                                        ->required()
                                        ->lazy()
                                        ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->label(trans('adm/form.slug'))
                                        ->required()
                                        ->unique(Tag::class, 'slug', ignoreRecord: true)->columnSpanFull(),
                                ]),


                        ])
                        ->collapsible(),

                    Section::make('Thumbnail')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('thumb')
                                ->collection('thumbs')
                                ->disableLabel(),
                        ])
                        ->collapsible(),

                    Section::make('Settings')
                        ->schema([
                            DateTimePicker::make('created_at')
                                ->label(trans('adm/form.date'))
                                ->default(Carbon::now()),
                            Select::make('lang')
                                ->label(trans('adm/form.lang'))
                                ->options(
                                    admLanguages()
                                )
                                ->default(admDefaultLanguage()),
                            Toggle::make('is_enabled')
                                ->label(trans('adm/form.is_enabled'))
                                ->default(true)
                        ])->collapsible(),

                ])->columnSpan(1),

            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(trans('adm/form.id'))
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(trans('adm/form.thumbnail'))
                    ->collection('thumbs'),
                TextColumn::make('title')
                    ->label(trans('adm/form.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(trans('adm/form.slug')),

                TextColumn::make('type')
                    ->label(trans('adm/form.post_type'))
                    ->sortable(),

                TagsColumn::make('categories.title')
                    ->label(trans('adm/form.categories'))
                    ->separator(','),
                TagsColumn::make('tags.title')
                    ->label(trans('adm/form.tags'))
                    ->separator(','),
                IconColumn::make('is_enabled')
                    ->label(trans('adm/form.is_enabled'))
                    ->boolean(),
                TextColumn::make('lang')
                    ->label(trans('adm/form.lang'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(trans('adm/form.date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(trans('adm/form.date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('locales')
                    ->description(fn ($record): string => $record->getTranslationLocales(), position: 'above')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('type')
                    ->multiple()
                    ->options(admPostTypes()),
                Filter::make('only_enabled')
                    ->query(fn (Builder $query): Builder => $query->where('is_enabled', true))
                    ->toggle()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                ActionAdmTranslationMapper::make('translate')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            PostStatsOverview::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
