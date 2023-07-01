<?php

namespace App\Filament\Pages;

use Phpsa\FilamentAuthentication\Pages\Profile as PagesProfile;

class Profile extends PagesProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.profile';
}
