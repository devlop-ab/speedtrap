<input type="hidden"
    name="{{ $inputName }}"
    value="{{ $inputValue }}"
>

@if ($speedtrapWasTriggered)
    {{ $slot }}
@endif
