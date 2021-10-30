@props([
  'id'       => null,
  'maxWidth' => null,
  'style'    => 'danger',
])

@php
  switch ($style) {
    case 'info':
      $icon_color      = 'text-purple-600';
      $icon_background = 'bg-purple-100';
      break;
    case 'warning':
      $icon_color      = 'text-yellow-600';
      $icon_background = 'bg-yellow-100';
      break;
    case 'danger': // Danger is Default.
    default:
      $icon_color = 'text-red-600';
      $icon_background = 'bg-red-100';
      break;
  }
@endphp

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
  <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
    <div class="sm:flex sm:items-start">
      <div class="{{ $icon_background }} flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto rounded-full sm:mx-0 sm:h-10 sm:w-10">
        <svg class="{{$icon_color}} w-6 h-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
      </div>

      <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
        <h3 class="text-lg">
          {{ $title }}
        </h3>

        <div class="mt-2">
          {{ $content }}
        </div>
      </div>
    </div>
  </div>

  <div class="px-6 py-4 text-right bg-gray-100">
    {{ $footer }}
  </div>
</x-jet-modal>
