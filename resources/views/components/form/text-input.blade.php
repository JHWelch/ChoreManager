@props([
  'label'       => ucfirst($name),
  'prefix'      => '',
  'placeholder' => '',
  'name',
])

<div>
  <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
  <div class="mt-1">
    <input
      {{ $attributes->merge([
        'wire:model.defer' => $prefix . '.' . $name,
        'type'             => 'text',
        'class'            => 'shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md',
        'id'               => $name,
        'placeholder'      => $placeholder,
      ]) }}
    />
  </div>
</div>
