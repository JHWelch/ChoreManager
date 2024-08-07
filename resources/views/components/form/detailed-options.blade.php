@props([
  'title', // Screenreader title.
  'options' => [],
  'value'
])

<fieldset x-data="{ selectedOption: @entangle($value).live }">
  <legend class="sr-only">
    {{ __($title) }}
  </legend>

  <div class="-space-y-px bg-white rounded-md">
    @foreach (array_values($options) as $index => $option)
      <x-form.detailed-options-option
        :label="$option['label']"
        :value="$option['value']"
        :description="$option['description']"
        :category="Str::snake($title)"
        :position="match ($index) {
          0                   => 'top',
          count($options) - 1 => 'bottom',
          default             => 'center',
        }"
      />
    @endforeach
  </div>
</fieldset>
