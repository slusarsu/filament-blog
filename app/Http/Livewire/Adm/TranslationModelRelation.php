<?php

namespace App\Http\Livewire\Adm;

use Livewire\Component;

class TranslationModelRelation extends Component
{

    public $record;

    public function mount($record)
    {
        $this->record = $record;

    }
    public function render()
    {
        return view('livewire.adm.translation-model-relation');
    }
}
