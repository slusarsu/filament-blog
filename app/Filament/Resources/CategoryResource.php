<?php

namespace App\Filament\Resources;

use App\Adm\Actions\ActionAdmTranslationMapper;
use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationLabel(): string
    {
        return trans('adm/dashboard.categories');
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
                                ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                            TextInput::make('slug')
                                ->label(trans('adm/form.slug'))
                                ->required()
                                ->unique(self::getModel(), 'slug', ignoreRecord: true),

                            TextInput::make('order')
                                ->label(trans('adm/form.order'))
                                ->integer(true)
                                ->default(0),

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
                                })
                                ->searchable(),

                            TinyEditor::make('content')
                                ->label(trans('adm/form.content'))
                                ->fileAttachmentsDisk('local')
                                ->fileAttachmentsVisibility('storage')
                                ->fileAttachmentsDirectory('public/uploads')
                                ->setConvertUrls(false)
                        ]),

                    Section::make('SEO')
                        ->schema([
                            TextInput::make('seo_title')
                                ->label(trans('adm/form.seo_title'))
                                ->columnSpan('full'),
                            Textarea::make('seo_text_keys')
                                ->label(trans('adm/form.seo_text_keys'))
                                ->columnSpan('full'),
                            Textarea::make('seo_description')
                                ->label(trans('adm/form.seo_description'))
                                ->columnSpan('full'),
                        ])->collapsible()->collapsed(),


                ])->columnSpan(3),

                Group::make()->schema([

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
                                ->label(trans('adm/form.created_at'))
                                ->default(Carbon::now()),

                            Select::make('lang')
                                ->label(trans('adm/form.lang'))
                                ->options(
                                    admLanguages()
                                )
                                ->default(admDefaultLanguage()),
                            Toggle::make('is_enabled')
                                ->label(trans('adm/form.is_enabled'))
                                ->default(true),
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
                    ->label(trans('adm/form.id'))
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(trans('adm/form.thumbnail'))
                    ->collection('categories'),
                TextColumn::make('title')
                    ->label(trans('adm/form.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(trans('adm/form.slug')),
                TextColumn::make('order')
                    ->label(trans('adm/form.order'))
                    ->sortable(),
                TextColumn::make('post_type')
                    ->label(trans('adm/form.post_type'))
                    ->sortable(),
                TextColumn::make('parent.title')
                    ->label(trans('adm/form.title'))
                    ->sortable(),
                IconColumn::make('is_enabled')
                    ->label(trans('adm/form.is_enabled'))
                    ->boolean(),
                TextColumn::make('lang')
                    ->label(trans('adm/form.lang'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(trans('adm/form.date'))
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('post_type')
                    ->multiple()
                    ->options(admPostTypes()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ActionAdmTranslationMapper::make('translate')
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
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
