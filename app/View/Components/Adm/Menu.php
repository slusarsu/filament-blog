<?php

namespace App\View\Components\Adm;

use App\Models\MenuItem;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Builder;

class Menu extends Component
{
    public null|Builder|Model $menu;
    public $items;

    /**
     * Create a new component instance.
     */
    public function __construct(string $slug)
    {
        $this->menu = \App\Models\Menu::query()
            ->where('slug', $slug)
            ->with('menu_items')
            ->lang()
            ->first();

        $this->items = MenuItem::tree($this->menu->id);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.adm.menu');
    }
}
