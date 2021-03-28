<div x-data="{ show: false }" class="flex flex-col justify-center">
  <button x-on:click="show = true">
    <x-icons.clock class="text-indigo-400 w-11 h-11" />
  </button>

  <div
    x-show="show"
    x-cloak
    @click.away="show = false"
    x-on:choreinstanceupdated.window="show = false"
    x-transition:enter="transform ease-out duration-200 transition"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="absolute -top-0.5 right-16 z-50 flex flex-col items-center justify-between w-36 space-y-2.5 p-2.5 bg-indigo-400 border border-indigo-500 rounded-xl"
  >
    <span class="font-semibold">Snooze Until</span>
    <x-popover-button click="snoozeUntilTomorrow">
      <span class="text-4xl font-bold">+1</span>

      <button class="font-semibold">Tomorrow</button>
    </x-popover-button>

    <x-popover-button click="snoozeUntilWeekend">
      <x-icons.calendar class="w-12 h-12"/>

      <button class="font-semibold">Weekend</button>
    </x-popover-button>
  </div>
</div>
