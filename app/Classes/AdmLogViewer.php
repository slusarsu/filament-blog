<?php

namespace App\Classes;

use FilipFonal\FilamentLogManager\Pages\Logs;

class AdmLogViewer extends Logs
{
    protected static string | array $middlewares = ['CheckAdminAccess'];
    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }
}
