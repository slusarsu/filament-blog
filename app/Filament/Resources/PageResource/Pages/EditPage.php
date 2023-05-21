<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public function getRecordTitle(): string
    {
        return trans('adm/dashboard.page');
    }

    public function getModelLabel(): string
    {
        $resource = static::getResource();

        if (! $resource::hasRecordTitle()) {
            return Str::headline($resource::getModelLabel());
        }

        return $resource::getRecordTitle($this->getRecord());
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
