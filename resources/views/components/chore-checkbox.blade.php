@props([
  'checked' => false,
])

<button
  x-data
  @click.prevent="$dispatch('checked')"
  class="flex items-center justify-center border-4 border-violet-400 rounded-md w-9 h-9 hover:bg-violet-200 dark:border-violet-600 dark:hover:bg-violet-800"
>
  @if ($checked)
    <x-icons.check class="text-violet-700" />
  @endif

</button>
