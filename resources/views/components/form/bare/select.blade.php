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
    'class'       => 'block w-full py-2 pl-3 pr-10 mt-1 text-base border-gray-300 rounded-md focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm',
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
