<?php

namespace App\Filament\Pages;

use App\Models\AdmTranslation;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdmTranslationSelectors extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.adm-translation-selectors';

    public string $model_type;
    public $record;
    public array $locales;
    public $records;
    public string $prevUrl;
    public null|Builder|Model $currentTranslation;
    /**
     * @var Builder[]|Collection
     */
    public array|Collection $allTranslations;


    public function mount(Request $request): void
    {
        $this->model_type = "App\Models\\".$request->model_type;
        $this->record = $this->model_type::query()->where('id', $request->record)->first();
        $this->locales = admLanguages();
        $this->records = $this->getAllRecords();
        $this->addLocaleProperties();
        $this->prevUrl = url()->previous();
        $this->currentTranslation = $this->getCurrentTranslation();
        if($this->currentTranslation) {
            $this->hash = $this->currentTranslation->hash;
            $this->allTranslations = $this->getAllTranslations();
            $this->formFillRecords();
        }
    }

    public function getAllRecords()
    {
        return $this->model_type::query()->where('lang', '!=', $this->record->lang)->get();
    }

    public function formFillRecords()
    {
        $records = [];

        foreach ($this->allTranslations as $item) {
            $records[$item->lang] = $item->model_id;
        }

        $this->form->fill($records);
    }

    public function getCurrentTranslation(): Model|Builder|null
    {
        return AdmTranslation::query()
            ->where('model_type', $this->model_type)
            ->where('model_id', $this->record->id)
            ->first();
    }

    public function getAllTranslations(): Collection|array
    {
        return AdmTranslation::query()
            ->where('hash', $this->hash)
            ->whereIn('lang', array_keys(admLanguages()))
            ->get();
    }

    private function addLocaleProperties(): void
    {
        foreach ($this->locales as $locale => $name) {
            $this->$locale = '';
        }
    }

    public function prepareSelectors(): array
    {
        $formSelectors = [];

        foreach ($this->locales as $locale => $name) {
            $records = $this->records?->where('lang', $locale)->pluck('title', 'id')->all();
            if(!empty($this->allTranslations)) {
                $selected = $this->allTranslations->where('lang', $locale)->first();
            }

            if(!$records) continue;

            $formSelectors[] = Select::make($locale)
                ->label($name)
                ->options($records)
                ->searchable();
        }

        return $formSelectors;
    }

    public function submit(): void
    {
        $languages = $this->form->getState();
        $languages[$this->record->lang] = $this->record->id;
        $hash = Str::uuid();

        foreach ($languages as $lang => $model_id) {
            if(!$model_id) {
//                AdmTranslation::query()->create([
//                    'model_type' => $this->model_type,
//                    'model_id' => $model_id,
//                    'lang' => $lang,
//                    'hash' => $hash,
//                ]);
                continue;
            }
            AdmTranslation::query()->create([
                'model_type' => $this->model_type,
                'model_id' => $model_id,
                'lang' => $lang,
                'hash' => $hash,
            ]);
        }


        $this->redirect($this->prevUrl);
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema($this->prepareSelectors())
        ];

    }
}
