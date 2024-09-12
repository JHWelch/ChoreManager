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
      'class'            => 'w-full block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm',
      'id'               => $name,
      'placeholder'      => $placeholder,
    ]) }}
  />
</div>
