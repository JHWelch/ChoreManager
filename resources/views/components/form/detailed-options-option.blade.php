@props([
  'label',
  'category',
  'value'       => $label,
  'description' => '',
  'position'    => 'center',
])

@php
    $position_classes = match($position) {
      'top' => 'rounded-tl-md rounded-tr-md',
      'bottom' => 'rounded-bl-md rounded-br-md',
      default => '',
    };

    $id_prefix = "{$category}_{$value}";
@endphp

<label x-radio-group-option=""
  class="{{ $position_classes }} relative z-10 flex p-4 border border-purple-200 cursor-pointer bg-purple-50"
  x-state:on="Checked" x-state:off="Not Checked"
  :class="{ 'bg-purple-50 border-purple-200 z-10': selectedOption === '{{ $value }}', 'border-gray-200': !(selectedOption === '{{ $value }}') }"
>
  <input type="radio" x-model="selectedOption" name="privacy_setting" value="{{ $value }}"
    class="h-4 w-4 mt-0.5 cursor-pointer text-purple-600 border-gray-300 focus:ring-purple-500"
    aria-labelledby="{{ $id_prefix }}-label" aria-describedby="{{ $id_prefix }}-description"
  >

  <div class="flex flex-col ml-3">
    <span id="{{ $id_prefix }}-label" class="block text-sm font-medium text-purple-900"
      x-state:on="Checked" x-state:off="Not Checked"
      :class="{ 'text-purple-900': selectedOption === '{{ $value }}', 'text-gray-900': !(selectedOption === '{{ $value }}') }">
      {{ __($label) }}
    </span>

    <span id="{{ $id_prefix }}-description" class="block text-sm text-purple-700" x-state:on="Checked"
      x-state:off="Not Checked"
      :class="{ 'text-purple-700': selectedOption === '{{ $value }}', 'text-gray-500': !(selectedOption === '{{ $value }}') }">
      {{ __($description) }}
    </span>
  </div>
</label>
