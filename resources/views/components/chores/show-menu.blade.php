@props([
  'class' => ''
])

<x-dropdown-menu :class="$class">
  <x-dropdown-option click="$toggle('showDeleteConfirmation')" status="danger">
    Delete
  </x-dropdown-option>
</x-dropdown-menu>
