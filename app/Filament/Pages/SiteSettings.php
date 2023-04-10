<?php

namespace App\Filament\Pages;

use App\Adm\Services\TemplateService;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Spatie\Valuestore\Valuestore;

class SiteSettings extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'filament.pages.site-settings';

    protected ?string $heading = 'Site Settings';

    protected static ?int $navigationSort = 3;

    public string $path = '';

    protected ?Valuestore $valueStore;

    public ?string $name = '';

    public ?string $keyWords = '';

    public ?string $description = '';

    public ?string $template = 'default';

    public bool $isEnabled = true;

    private mixed $templateService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->path = storage_path('app/site_settings.json');
        $this->valueStore = Valuestore::make($this->path);
        $this->templateService = resolve(TemplateService::class);
    }

    public function mount(): void
    {
        $this->form->fill([
            'name' => $this->valueStore->get('name'),
            'keyWords' => $this->valueStore->get('keyWords'),
            'description' => $this->valueStore->get('description'),
            'isEnabled' => $this->valueStore->get('isEnabled') ?? true,
            'template' => $this->valueStore->get('template') ?? 'default',
        ]);
    }

    public function submit(): void
    {
        $this->valueStore->put('name', $this->name);
        $this->valueStore->put('keyWords', $this->keyWords);
        $this->valueStore->put('description', $this->description);
        $this->valueStore->put('isEnabled', $this->isEnabled);
        $this->valueStore->put('template', $this->template);

        Notification::make()
            ->title('Saved successfully')
            ->icon('heroicon-o-sparkles')
            ->iconColor('success')
            ->send();
    }

    protected function getFormSchema(): array
    {

        return [
            Card::make()->schema([
                TextInput::make('name'),
                TextInput::make('keyWords'),
                Textarea::make('description'),
                Select::make('template')
                    ->options(
                        $this->templateService->getAllTemplatesNames()
                    )
                    ->default('default')
                    ->required(),
                Toggle::make('isEnabled')->default(true),
            ])
        ];

    }
}
