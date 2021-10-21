@props([
  'checked' => false,
])

<button
  x-data
  @click.prevent="$dispatch('checked')"
  class="flex items-center justify-center border-4 border-indigo-400 rounded-md w-9 h-9 hover:bg-indigo-200"
>
  @if ($checked)
    <x-icons.check class="text-indigo-700" />
  @endif

</button>
