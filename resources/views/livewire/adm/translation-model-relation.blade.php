<div>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="py-6">
            <button type="submit" wire:click="submit"
                    class="filament-button filament-button-size-md font-medium rounded-lg border transition-colors outline-none focus:ring-offset-2 focus:ring-2 focus:ring-inset dark:focus:ring-offset-0 min-h-[2.25rem] px-4 text-sm text-white shadow focus:ring-white border-transparent bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 focus:ring-offset-primary-700">
                Submit
            </button>
        </div>
    </form>
</div>
