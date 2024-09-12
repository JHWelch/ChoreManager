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
      'class'            => 'w-full block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-md shadow-sm',
      'id'               => $name,
      'placeholder'      => $placeholder,
    ]) }}
  />
</div>
