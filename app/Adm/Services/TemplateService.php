<?php

namespace App\Adm\Services;

use Storage;

class TemplateService
{
    public function getAllTemplates(): array
    {
        $allTemplates = Storage::disk('templates')->directories();

        $templates = [];

        foreach ($allTemplates as $template) {
            $file = include resource_path('views/templates/'.$template.'/settings.php');
            $templates[$template] = $file;
        }

        return $templates;
    }
}
