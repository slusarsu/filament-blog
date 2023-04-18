<?php

use Spatie\Valuestore\Valuestore;

function siteSetting() {
    return Valuestore::make(storage_path('app/site_settings.json'));
}


function getTemplatePath(): string
{
    return 'resources/views/' . siteSetting()->get('template') .'/';
}

function admAsset(string $filePath): string
{
    return asset('templates/'.siteSetting()->get('template').'/'. $filePath);
}

