@props([
  'click'    => '',
  'selected' => false,
  'position' => 'center'
])

@php
  $class = 'relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500';

  if ($position === 'left') {
    $class = $class . ' rounded-l-md';
  } else if ($position === 'right') {
    $class = $class . ' rounded-r-md';
  } else {
    $class = '';
  }

  $class = $class . ' ' . ($selected ? 'text-gray-800' : 'text-gray-500');
@endphp

<button
  wire:click="{{ $click }}"
  type="button"
  class="{{ $class }}"
  aria-current="{{ $selected ? 'true' : 'false' }}"
>
  {{ $slot }}

  @if ($selected)
    <span aria-hidden="true" class="bg-purple-500 absolute inset-x-0 bottom-0 h-0.5"></span>
  @else
    <span aria-hidden="true" class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
  @endif
</button>
