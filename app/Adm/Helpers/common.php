<?php

use Spatie\Valuestore\Valuestore;

function siteSetting() {
    return Valuestore::make(storage_path('app/site_settings.json'));
}


function getTemplatePath(): string
{
    return 'resources/views/' . siteSetting()->get('template') .'/';
}

function templateAssetsPath(string $filePath): string
{
    return asset('assets/'.siteSetting()->get('template').'/'. $filePath);
}

