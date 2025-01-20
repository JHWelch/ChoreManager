@props([
  'class' => '',
  'chore_id',
])

<x-dropdown-menu :class="$class">
  <x-dropdown-option wire:click="$dispatch('openModal', { component: 'chores.modals.custom-complete', arguments: { chore: {{ $chore_id }} }})">
    Custom Complete
  </x-dropdown-option>

  <x-dropdown-option x-clipboard="'{{ route('chores.complete.index', ['chore' => $chore_id]) }}'">
    Copy Complete URL
  </x-dropdown-option>

  <x-dropdown-option wire:click="$toggle('showDeleteConfirmation')" status="danger">
    Delete
  </x-dropdown-option>
</x-dropdown-menu>
