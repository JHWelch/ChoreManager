@props([
  'label'  => ucfirst($name),
  'name',
])

<label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
