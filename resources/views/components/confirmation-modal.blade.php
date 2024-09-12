@props([
  'id'       => null,
  'maxWidth' => null,
  'style'    => 'danger',
])

@php
  switch ($style) {
    case 'info':
      $icon_color      = 'text-violet-600 dark:text-violet-400';
      $icon_background = 'bg-violet-100';
      break;
    case 'warning':
      $icon_color      = 'text-yellow-600 dark:text-yellow-400';
      $icon_background = 'bg-yellow-100';
      break;
    case 'danger': // Danger is Default.
    default:
      $icon_color = 'text-red-600 dark:text-red-400';
      $icon_background = 'bg-red-100';
      break;
  }
@endphp

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
  <div class="px-4 pt-5 pb-4 bg-white dark:bg-gray-800 sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
      <div class="{{ $icon_background }} flex items-center justify-center w-12 h-12 mx-auto rounded-full shrink-0 sm:mx-0 sm:h-10 sm:w-10">
        <svg class="{{ $icon_color }} w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
      </div>

      <div class="mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          {{ $title }}
        </h3>

        <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
          {{ $content }}
        </div>
      </div>
    </div>
  </div>

  <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 dark:bg-gray-800 text-end">
    {{ $footer }}
  </div>
</x-modal>
