@props([
  'prefix'      => '',
  'placeholder' => '',
  'type'        => 'text',
  'name'        => '',
])

<div class="mt-1">
  <input
    {{ $attributes->merge([
      'wire:model' => $prefix . ($prefix ? '.' : '') . $name,
      'type'             => $type,
      'class'            => 'shadow-sm focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md',
      'id'               => $name,
      'placeholder'      => $placeholder,
    ]) }}
  />
</div>
