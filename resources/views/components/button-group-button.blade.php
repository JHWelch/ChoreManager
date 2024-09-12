@props([
  'click'    => '',
  'selected' => false,
  'position' => 'center'
])

<button
  wire:click="{{ $click }}"
  type="button"
  aria-current="{{ $selected ? 'true' : 'false' }}"
  @class([
    'relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white border border-gray-300 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500',
    'dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-700 dark:focus:ring-purple-800 dark:focus:border-purple-800',
    'rounded-l-md' => $position === 'left',
    'rounded-r-md' => $position === 'right',
    'text-gray-800 dark:text-gray-300' => $selected,
    'text-gray-500 dark:text-gray-400' => !$selected,
  ])
>
  {{ $slot }}

  @if ($selected)
    <span aria-hidden="true" class="bg-purple-500 dark:bg-purple-800 absolute inset-x-0 bottom-0 h-0.5"></span>
  @else
    <span aria-hidden="true" class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"></span>
  @endif
</button>
