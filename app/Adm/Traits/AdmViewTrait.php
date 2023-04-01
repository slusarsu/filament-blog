<?php

namespace App\Adm\Traits;

trait AdmViewTrait
{
    public function admView(string $view, array $params)
    {
        $template = siteSetting()->get('template');

        $viewPath = 'templates/'. $template .'/'. $view;

        return view($viewPath, $params);
    }
}
