@props([
  'checked' => false,
])

<button
  x-data
  @click.prevent="$dispatch('checked')"
  class="flex items-center justify-center border-4 border-purple-400 rounded-md w-9 h-9 hover:bg-purple-200"
>
  @if ($checked)
    <x-icons.check class="text-purple-700" />
  @endif

</button>
