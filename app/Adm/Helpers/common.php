<?php
use Spatie\Valuestore\Valuestore;

function siteSetting() {
    return Valuestore::make(app_path('Adm/Settings/site_settings.json'));
}


function getFullTemplatePath(): string
{
    return 'resources/views/' . siteSetting()->get('template') .'/';
}

function admView(string $bladeName, array $params = [])
{
    return view('templates.'.siteSetting()->get('template') .'.'.$bladeName, $params);
}

function admAsset(string $filePath): string
{
    return asset('templates/'.siteSetting()->get('template').'/'. $filePath);
}

