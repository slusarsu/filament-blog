<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Filament\Resources\MenuResource\RelationManagers;
use App\Models\Menu;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Str;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationLabel(): string
    {
        return trans('adm/dashboard.menu');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
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

                    Toggle::make('is_enabled')
                        ->default(true)
                        ->label(trans('adm/form.is_enabled')),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label(trans('adm/form.id'))
                    ->sortable(),
                TextColumn::make('title')
                    ->label(trans('adm/form.title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(trans('adm/form.slug'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('is_enabled')
                    ->label(trans('adm/form.is_enabled'))
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label(trans('adm/form.date'))
                    ->label('date')
                    ->date()
                    ->sortable()
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
            RelationManagers\MenuItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
