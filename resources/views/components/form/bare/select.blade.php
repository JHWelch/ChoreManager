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
    'class'       => 'w-full block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-md shadow-sm',
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
