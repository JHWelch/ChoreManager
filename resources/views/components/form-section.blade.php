@props(['submit'])

<div {{ $attributes->merge(['class' => 'md:grid md:grid-cols-3 md:gap-6']) }}>
  <x-section-title>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="description">{{ $description }}</x-slot>
  </x-section-title>

  <div class="mt-5 md:mt-0 md:col-span-2">
    <form wire:submit="{{ $submit }}">
      <div class="overflow-hidden shadow sm:rounded-md">
        <div class="px-4 py-5 bg-white sm:p-6">
          <div class="grid grid-cols-6 gap-6">
            {{ $form }}
          </div>
        </div>

        @if (isset($actions))
          <div class="flex items-center justify-end px-4 py-3 text-right bg-gray-50 sm:px-6">
            {{ $actions }}
          </div>
        @endif
      </div>
    </form>
  </div>
</div>
