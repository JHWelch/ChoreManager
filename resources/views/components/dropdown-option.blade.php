@props([
  'option' => '',
  'status' => '',
  'click'  => '',
])

@php($text_color = match ($status) {
  'danger' => 'text-red-500 hover:text-red-800 dark:text-red-400 dark:hover:text-red-400',
  default  => 'text-violet-500 hover:text-violet-900 dark:text-violet-200 dark:hover:text-violet-300',
})

<button
  {{ $attributes->merge([
    'x-on:click' => 'show = false',
    'class' => 'block w-full px-4 py-2 text-sm font-medium text-left ' . $text_color . ' whitespace-nowrap hover:bg-gray-100 focus:outline-none dark:hover:bg-gray-700',
  ]) }}
  data-dropdown-option="{{ $option }}"
  role="menuitem"
>
  {{ $slot }}
</button>
