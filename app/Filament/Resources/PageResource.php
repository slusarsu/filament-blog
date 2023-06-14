<?php

namespace App\Filament\Resources;

use App\Adm\Services\PageService;
use App\Adm\Services\TemplateService;
use App\Adm\Services\TranslationService;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Filament\Resources\PageResource\Widgets\PageStatsOverview;
use App\Models\Page;
use App\Models\Seo;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\MorphToSelect;
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
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Builder as FromBuilder;
use Illuminate\Database\Eloquent\Model;
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

    protected static function getNavigationLabel(): string
    {
        return trans('adm/dashboard.pages');
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
                                ->unique(self::getModel(), 'slug', ignoreRecord: true)->columnSpanFull(),

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
                                        ->multiple()->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                        ->enableReordering()
                                        ->disableLabel(),
                                ]),
                            Tab::make('Custom Fields')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    FromBuilder::make('custom_fields')
                                        ->blocks([
                                            Block::make('text_input')
                                                ->schema([
                                                    TextInput::make('field_name'),
                                                    TextInput::make('text')
                                                ])
                                                ->label(fn (array $state): ?string => $state['field_name'] ?? null),
                                            Block::make('paragraph')
                                                ->schema([
                                                    TextInput::make('field_name'),
                                                    Textarea::make('content')
                                                        ->label('Paragraph')
                                                        ->required(),
                                                ]),
                                            Block::make('content')
                                                ->schema([
                                                    TextInput::make('field_name'),
                                                    TinyEditor::make('content')
                                                        ->label(trans('adm/form.content'))
                                                        ->fileAttachmentsDisk('local')
                                                        ->fileAttachmentsDirectory('public/uploads')
                                                        ->fileAttachmentsVisibility('storage')
                                                        ->setConvertUrls(false)
                                                ]),
                                            Block::make('image')
                                                ->schema([
                                                    TextInput::make('field_name'),
                                                    FileUpload::make('url')
                                                        ->label('Image')
                                                        ->image()
                                                        ->required(),
                                                    TextInput::make('alt')
                                                        ->label('Alt text')
                                                        ->required(),
                                                ]),
                                        ])
                                ]),
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

                    Section::make('Thumbnail')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('thumb')
                                ->label(trans('adm/form.thumbnail'))
                                ->collection('thumbs')
                                ->disableLabel(),
                        ])
                        ->collapsible(),

                    Section::make('Settings')
                        ->schema([
                            DateTimePicker::make('created_at')
                                ->label(trans('adm/form.created_at'))
                                ->default(Carbon::now()),
                            Select::make('template')
                                ->label(trans('adm/form.template'))
                                ->options(resolve(TemplateService::class)->getCurrentTemplatePageNames())
                                ->default('page')
                                ->required(),
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
                    ->collection('thumbs'),
                TextColumn::make('title')
                    ->label(trans('adm/form.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(trans('adm/form.slug')),

                IconColumn::make('is_enabled')
                    ->label(trans('adm/form.is_enabled'))
                    ->boolean(),
                TextColumn::make('lang')
                    ->label(trans('adm/form.lang'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(trans('adm/form.date'))
                    ->label('date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('only_enabled')
                    ->query(fn (Builder $query): Builder => $query->where('is_enabled', true))
                    ->toggle()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Action::make('translate')
                    ->action(function (array $data, Action $action): void {
                        resolve(TranslationService::class)->TranslateModel(static::$model, $action->getRecord(), $data);
                    })
                    ->form(fn (Action $action): array => [
                        Select::make('model_records_id')
                            ->label('Pages')
                            ->options(resolve(TranslationService::class)->getAllTranslationList(static::$model, $action->getRecord()))
                            ->multiple()
                            ->required(),
                    ]),
                Action::make('advance')
                    ->action(fn () => '')
                    ->modalContent(function ($record) {
                        return view('livewire.adm.translation-model-relation', ['record' => $record]);
                    })
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
            PageStatsOverview::class
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
