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

    public function templateRecoursePath(): string
    {
        return 'templates.'.siteSetting()->get('template');
    }

    public function getTemplateSettings(string $templateName): array
    {
        if(empty($templateName)) {
            return [];
        }
        return include resource_path('views/templates/'.$templateName.'/inc/settings.php') ?? [];
    }

    public function getTemplateFunctions(string $templateName)
    {
        if(empty($templateName)) {
            return null;
        }
        return include resource_path('views/templates/'.$templateName.'/inc/functions.php');
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
        $templates = [];

        foreach ($this->templatesDirectories as $template) {
            $file = $this->getAllTemplatesSettings($template);

            if(!empty($file[$template]['name'])) {
                $templates[$template] = $file[$template]['name'];
            } else {
                $templates[$template] = $template;
            }
        }

        return $templates;
    }

    public function getCurrentTemplatePageNames(): array
    {
        $templateName = siteSetting()->get('template');
        $pagePaths = Storage::disk('templates')->files($templateName.'/pages');
        $templates = [];
        foreach ($pagePaths as $item) {
            $itemArr = explode('/', $item);
            $last = end($itemArr);
            $result = str_replace('.blade.php','',$last);
            $templates[$result] = $result;
        }

        return $templates;
    }

    public function templateView(string $bladeName, array $params = [])
    {
        return view($this->templateRecoursePath() .'.'.$bladeName, $params);
    }
}
