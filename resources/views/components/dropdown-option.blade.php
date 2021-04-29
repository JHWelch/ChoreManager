@props([
  'option' => '',
  'status' => '',
  'click'  => '',
])

@php($text_color = match ($status) {
  'danger' => 'text-red-500 hover:text-red-800',
  default  => 'text-indigo-500 hover:text-indigo-900',
})

<button
  x-on:click="show = false"
  data-dropdown-option="{{ $option }}"
  class="block w-full px-4 py-2 text-sm font-medium text-left {{ $text_color }} whitespace-nowrap hover:bg-gray-100 focus:outline-none'"
  role="menuitem"
  wire:click="{{ $click }}"
>
  {{ $slot }}
</button>
