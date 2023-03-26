<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([

                    Card::make()
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->lazy()
                                ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                ->columnSpanFull(),

                            TextInput::make('slug')
                                ->required()
                                ->unique(self::getModel(), 'slug', ignoreRecord: true)
                                ->columnSpanFull(),

                            TinyEditor::make('short')
                                ->fileAttachmentsDisk('local')
                                ->fileAttachmentsVisibility('storage')
                                ->fileAttachmentsDirectory('public/uploads')
                        ]),

                    Section::make('Content')
                        ->schema([
                            TinyEditor::make('content')
                                ->fileAttachmentsDisk('local')
                                ->fileAttachmentsVisibility('storage')
                                ->fileAttachmentsDirectory('public/uploads')
                        ]),

                    Section::make('Images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->collection('images')
                                ->multiple()
                                ->disableLabel(),
                        ])
                        ->collapsible(),

                ])->columnSpan(3),

                Group::make()->schema([

                    Section::make('Taxonomy')
                        ->schema([
                            Select::make('categories')
                                ->multiple()
                                ->preload()
                                ->relationship('categories', 'title')
                                ->createOptionForm([
                                    TextInput::make('title')
                                        ->required()
                                        ->lazy()
                                        ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->required()
                                        ->unique(Category::class, 'slug', ignoreRecord: true)->columnSpanFull(),

                                    Select::make('parent_id')
                                        ->label('Parent')
                                        ->options(Category::all()->pluck('title', 'id'))
                                        ->searchable()
                                ]),

                            Select::make('tags')
                                ->multiple()
                                ->preload()
                                ->relationship('tags', 'title')
                                ->createOptionForm([
                                    TextInput::make('title')
                                        ->required()
                                        ->lazy()
                                        ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->columnSpanFull(),

                                    TextInput::make('slug')
                                        ->required()
                                        ->unique(Tag::class, 'slug', ignoreRecord: true)->columnSpanFull(),
                                ])
                        ])
                        ->collapsible(),

                    Section::make('Thumbnail')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('thumb')
                                ->collection('thumbs')
                                ->disableLabel(),
                        ])
                        ->collapsible(),

                    Section::make('SEO')
                        ->schema([
                            Textarea::make('seo_text_keys')->columnSpan('full'),
                            Textarea::make('seo_description')->columnSpan('full'),
                        ]),

                    Section::make('Settings')
                        ->schema([
                            DateTimePicker::make('created_at')->default(Carbon::now()),
                            Toggle::make('is_enabled')->default(true)
                        ]),

                ])->columnSpan(1),

            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug'),
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('posts'),
                TagsColumn::make('categories.title')
                    ->separator(','),
                TagsColumn::make('tags.title')
                    ->separator(','),
                IconColumn::make('is_enabled')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
