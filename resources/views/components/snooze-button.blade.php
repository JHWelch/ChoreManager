@props([
  'size'                => 'large',
  'right'               => 'right-16',
  'top'                 => '-top-6',
  'snoozeUntilTomorrow' => 'snoozeUntilTomorrow',
  'snoozeUntilWeekend'  => 'snoozeUntilWeekend',
])

@php
  $size_class = match($size) {
    'small'  => 'w-5 h-5',
    'medium' => 'w-8 h-8',
    'large'  => 'w-11 h-11',
  }
@endphp

<div x-data="{ show: false }" class="flex flex-col justify-center">
  <button x-on:click.prevent="show = true">
    <x-icons.clock class="text-purple-400 {{ $size_class }}" />
  </button>

  <div
    x-show="show"
    x-cloak
    @click.away="show = false"
    x-on:choreinstanceupdated.window="show = false"
    x-transition:enter="ease-out duration-200 transition"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="{{ implode(' ', [$top, $right]) }} absolute z-50 flex flex-col items-center justify-between w-36 space-y-2.5 p-2.5 bg-purple-400 border border-purple-500 rounded-xl"
  >
    <span class="font-semibold">Snooze Until</span>

    <x-popover-button click="{!! $snoozeUntilTomorrow !!}">
      <span class="text-4xl font-bold">+1</span>

      <span class="font-semibold">Tomorrow</span>
    </x-popover-button>

    <x-popover-button click="{!! $snoozeUntilWeekend !!}">
      <x-icons.calendar class="w-12 h-12"/>

      <span class="font-semibold">Weekend</span>
    </x-popover-button>
  </div>
</div>
