<?php

namespace App\Filament\Resources;

use App\Adm\Services\TemplateService;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
use Closure;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?int $navigationSort = 0;

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
                                ->unique(self::getModel(), 'slug', ignoreRecord: true)->columnSpanFull(),

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
                                ->fileAttachmentsDirectory('public/uploads'),

                        ]),

                    Tabs::make('Heading')
                        ->tabs([
                            Tab::make('Images')
                                ->icon('heroicon-o-film')
                                ->schema([
                                        Section::make('Images')
                                        ->schema([
                                            SpatieMediaLibraryFileUpload::make('media')
                                                ->collection('images')
                                                ->multiple()
                                                ->enableReordering()
                                                ->disableLabel(),
                                        ])
                                        ->collapsible(),
                                ]),
                            Tab::make('Custom Text Fields')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    Repeater::make('custom_text_fields')
                                        ->schema([
                                            TextInput::make('field_name')->lazy(),
                                            Textarea::make('text')
                                        ])
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['field_name'] ?? null)
                                        ->columns(1),
                                ]),
                        ]),


                ])->columnSpan(3),

                Group::make()->schema([

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
                            Select::make('template')
                                ->options(resolve(TemplateService::class)->getCurrentTemplatePageNames()),
                            Toggle::make('is_enabled')->default(true),
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
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection('thumbs'),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug'),

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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
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
