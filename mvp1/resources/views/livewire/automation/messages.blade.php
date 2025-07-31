<div>

    {{ $this->table }}

    @section('form')
        <div>
            @include('livewire.automation.custom-buttons', [
                'selectedLanguages' => $selectedLanguages,
                'availableLanguages' => $availableLanguages,
                'dropdownVisible' => $dropdownVisible,
            ])
        </div>
    @endsection
</div>