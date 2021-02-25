@props([
  'label'  => ucfirst($name),
  'prefix' => '',
  'name',
  'blankOption' => false,
  'options'     => [],
  'disabled'    => false,
])

<div>
  <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>

  <select
    {{ $attributes->merge([
      'wire:model.defer'  => $prefix . '.' . $name,
      'class'             => 'block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm',
      'id'                => $name,
      'name'              => $name,
    ]) }}
    {{ $disabled ? 'disabled' : '' }}
  >
    @if ($blankOption)
      <option value="">{{ is_string($blankOption) ? $blankOption : __('Select one') }}</option>
    @endif

    @foreach ($options as $option)
      <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
    @endforeach
  </select>
</div>
