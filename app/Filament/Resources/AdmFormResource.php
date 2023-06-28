<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdmFormResource\Pages;
use App\Filament\Resources\AdmFormResource\RelationManagers;
use App\Models\AdmForm;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class AdmFormResource extends Resource
{
    protected static ?string $model = AdmForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Form Configuration')->schema([
                    TextInput::make('title')
                        ->label(trans('adm/form.title'))
                        ->required()
                        ->lazy()
                        ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('slug')
                        ->label(trans('adm/form.slug'))
                        ->required()
                        ->unique(self::getModel(), 'slug', ignoreRecord: true),

                    TextInput::make('link_hash')
                        ->disabled()
                        ->default(Str::random(15)),

                    Toggle::make('is_enabled')
                        ->label(trans('adm/form.is_enabled'))
                        ->default(true),
                ])->collapsible(),

                Section::make('Add Form Fields')
                    ->schema([
                        Repeater::make('fields')
                            ->schema([
                                TextInput::make('field_name')
                                    ->required(),
                            ])
                            ->collapsible()
                    ])
                    ->collapsed()
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug'),
                TextColumn::make('link_hash')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(trans('adm/form.date'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AdmFormItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdmForms::route('/'),
            'create' => Pages\CreateAdmForm::route('/create'),
            'edit' => Pages\EditAdmForm::route('/{record}/edit'),
        ];
    }
}
