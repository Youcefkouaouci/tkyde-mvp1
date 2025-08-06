<x-filament-panels::page>  
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $this->getTitle() }}</h1>
            <div class="flex items-center justify-between gap-6">
                <div class="flex-1">
                    <p class="text-sm text-gray-600">
                        Optimize contact with guests by creating and enforcing automated messaging rules. Set up auto-responses for booking events and AI-raised questions, and also schedule essential messages for your guests.
                    </p>
                </div>
                {{-- Bouton affiché manuellement et commenté = problème de bouton en double
                <div class="flex-shrink-0">
                    @foreach($this->getHeaderActions() as $action)
                    {{ $action }}
                    @endforeach
                </div>--}}
            </div>
        </div>
        {{-- Tableau SANS headerActions --}}
        @livewire('automation.message-rules-table')
</x-filament-panels::page>