@props([
  'label'       => ucfirst($name),
  'prefix'      => '',
  'placeholder' => '',
  'class'       => null,
  'name',
])

<div class="mt-1">
  <textarea
    {{ $attributes->merge([
      'wire:model' => $prefix . '.' . $name,
      'class'            => $class ?? 'w-full block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-48',
      'id'               => $name,
      'placeholder'      => $placeholder,
    ]) }}
  >
  </textarea>
</div>
