@props([
  'label'       => ucfirst($name),
  'prefix'      => '',
  'placeholder' => '',
  'name',
])

<div class="mt-1">
  <textarea
    {{ $attributes->merge([
      'wire:model.defer' => $prefix . '.' . $name,
      'class'            => 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md',
      'id'               => $name,
      'placeholder'      => $placeholder,
    ]) }}
  >
  </textarea>
</div>