<?php

namespace App\View\Components\Adm;

use App\Adm\Services\TemplateService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    private array $siteSetting;
    private array $templateService;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->siteSetting = siteSetting()->all();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.adm.header', ['siteSetting' => $this->siteSetting]);
    }
}
