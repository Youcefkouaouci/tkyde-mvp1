<x-filament-panels::page>
    <x-app.heading :title="$this->getTitle()" />

    <p class="text-sm text-gray-600 mb-6">
        Optimize contact with guests by creating and enforcing automated messaging rules. Set up auto-responses for booking events and AI-raised questions, and also schedule essential messages for your guests.
    </p>

    @livewire('automation.message-rules-table')
</x-filament-panels::page>
