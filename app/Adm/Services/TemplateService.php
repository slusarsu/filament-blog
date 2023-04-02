<?php

namespace App\Adm\Services;

use Storage;

class TemplateService
{
    private array $templatesDirectories;

    public function __construct()
    {
        $this->templatesDirectories = Storage::disk('templates')->directories();
    }

    public function getTemplateSettings(string $templateName): array
    {
        if(empty($templateName)) {
            return [];
        }
        return include resource_path('views/templates/'.$templateName.'/settings.php') ?? [];
    }

    public function getAllTemplatesSettings(): array
    {
        $templates = [];

        foreach ($this->templatesDirectories as $template) {
            $templates[$template] = $this->getTemplateSettings($template);
        }

        return $templates;
    }

    public function getAllTemplatesNames(): array
    {
        $allTemplates = Storage::disk('templates')->directories();

        $templates = [];

        foreach ($allTemplates as $template) {
            $file = $this->getTemplateSettings($template);

            if(!empty($file['name'])) {
                $templates[$template] = $file['name'];
            }

        }

        return $templates;
    }
}
