<?php

namespace App\View\Components\Adm;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Widget extends Component
{
    private \App\Models\Widget $model;

    private string $slug;

    /**
     * Create a new component instance.
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;

        $this->model = new \App\Models\Widget();
    }

    public function getWidget()
    {
        return $this->model::query()->where('slug', $this->slug)->first();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $widget = $this->getWidget();

        return view('components.adm.widget', compact('widget'));
    }
}
