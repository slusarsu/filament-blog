<?php

namespace App\Http\Livewire\Adm;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TranslationModelRelation extends Component implements HasForms
{
    use InteractsWithForms;
    private string $model_type;
    public $record;
    public array $locales;
    private $records;

    public function mount($record)
    {
        $this->record = $record;
        $this->model_type = get_class($record);
        $this->locales = admLanguages();
        $this->records = $this->getAllRecords();
//        $this->fillForm();
    }

    public function getAllRecords()
    {
        return $this->model_type::query()->where('lang', '!=', $this->record->lang)->get();
    }

    private function fillForm()
    {
        $formItems = [];

        foreach ($this->locales as $locale => $name) {
            $records = $this->records?->where('lang', $locale)->pluck('title', 'id')->all();

            if(!$records) continue;

            $formItems[$locale] = '1';
        }

        $this->form->fill($formItems);
    }

    public function prepareSelectors(): array
    {
        $formSelectors = [];

        foreach ($this->locales as $locale => $name) {
            $records = $this->records?->where('lang', $locale)->pluck('title', 'id')->all();

            if(!$records) continue;

            $formSelectors[] = Select::make($locale)
                ->label($name)
                ->options($records)
                ->default([5])
                ->searchable();
        }

        return $formSelectors;
    }

    protected function getFormSchema(): array
    {
        return $this->prepareSelectors();
    }

    public function submit(): void
    {

    }
    public function render()
    {
        return view('livewire.adm.translation-model-relation');
    }
}
