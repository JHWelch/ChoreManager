@props([
  'option' => '',
  'status' => '',
  'click'  => '',
])

@php($text_color = match ($status) {
  'danger' => 'text-red-500 hover:text-red-800',
  default  => 'text-purple-500 hover:text-purple-900',
})

<button
  {{ $attributes->merge([
    'x-on:click' => 'show = false',
    'class' => 'block w-full px-4 py-2 text-sm font-medium text-left ' . $text_color . ' whitespace-nowrap hover:bg-gray-100 focus:outline-none'
  ]) }}
  data-dropdown-option="{{ $option }}"
  role="menuitem"
>
  {{ $slot }}
</button>
