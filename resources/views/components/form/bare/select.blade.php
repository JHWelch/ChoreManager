@props([
  'prefix' => '',
  'name',
  'blankOption' => false,
  'options'     => [],
  'disabled'    => false,
])

<select
  {{ $attributes->merge([
    'wire:model.live'  => $prefix ? $prefix . '.' . $name : $name,
    'class'       => 'w-full block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
    'id'          => $name,
    'name'        => $name,
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
