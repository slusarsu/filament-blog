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

    protected static ?string $navigationGroup = 'System';
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static string $view = 'filament.pages.site-settings';

    protected static ?int $navigationSort = 3;

    protected ?Valuestore $valueStore;

    public ?string $name = '';

    public ?string $keyWords = '';

    public ?string $description = '';

    public ?string $template = 'default';

    public bool $isEnabled = true;

    private mixed $templateService;

    protected static string | array $middlewares = ['CheckAdminAccess'];

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->valueStore = siteSetting();
        $this->templateService = resolve(TemplateService::class);
        $this->heading = trans('adm/dashboard.site_settings');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }

    protected static function getNavigationLabel(): string
    {
        return trans('adm/dashboard.site_settings');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $this->form->fill([
            'name' => $this->valueStore->get('name'),
            'keyWords' => $this->valueStore->get('keyWords'),
            'description' => $this->valueStore->get('description'),
            'isEnabled' => $this->valueStore->get('isEnabled') ?? true,
            'template' => $this->valueStore->get('template') ?? 'default',
            'default_language' => $this->valueStore->get('default_language') ?? 'en',
        ]);
    }

    public function submit(): void
    {
        $this->valueStore->put('name', $this->name);
        $this->valueStore->put('keyWords', $this->keyWords);
        $this->valueStore->put('description', $this->description);
        $this->valueStore->put('isEnabled', $this->isEnabled);
        $this->valueStore->put('template', $this->template);
        $this->valueStore->put('default_language', $this->default_language);

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
                TextInput::make('name')->label(trans('adm/form.name')),
                TextInput::make('keyWords')->label(trans('adm/form.key_words')),
                Textarea::make('description')->label(trans('adm/form.description')),
                Select::make('template')->label(trans('adm/form.template'))
                    ->options(
                        $this->templateService->getAllTemplatesNames()
                    )
                    ->default('default')
                    ->required(),
                Toggle::make('isEnabled')->default(true)->label(trans('adm/form.is_enabled')),
                Select::make('default_language')->label(trans('adm/form.default_lang'))
                    ->options(
                        admLanguages()
                    )
                    ->required(),
            ])
        ];

    }
}
