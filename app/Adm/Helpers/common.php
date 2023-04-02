<?php

use Spatie\Valuestore\Valuestore;

function siteSetting() {
    return Valuestore::make(storage_path('app/site_settings.json'));
}


