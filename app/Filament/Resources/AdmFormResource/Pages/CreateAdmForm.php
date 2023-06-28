<?php

namespace App\Filament\Resources\AdmFormResource\Pages;

use App\Filament\Resources\AdmFormResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateAdmForm extends CreateRecord
{
    protected static string $resource = AdmFormResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }
}
