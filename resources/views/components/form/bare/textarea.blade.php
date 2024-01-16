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
      'class'            => $class ?? 'shadow-sm focus:ring-purple-500 focus:border-purple-500 block w-full sm:text-sm border-gray-300 rounded-md h-48',
      'id'               => $name,
      'placeholder'      => $placeholder,
    ]) }}
  >
  </textarea>
</div>
