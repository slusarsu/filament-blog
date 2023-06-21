<?php

namespace App\Adm\Actions;

use Filament\Tables\Actions\Action;

class ActionAdmTranslationMapper
{
    public static function make(string $name = 'translate')
    {
        return Action::make($name)
            ->action(fn () => '')
            ->url(function ($record){
                return route('filament.pages.adm-translation-selectors', [
                    'record' => $record->id,
                    'model_type' => class_basename($record),
                ]);
            });
    }
}
