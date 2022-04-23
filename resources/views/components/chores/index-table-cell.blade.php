@props([
  'class' => 'text-gray-500',
  'chore',
])


<td>
  <a href="{{ route('chores.show', ['chore' => $chore]) }}">
    <div class="px-6 py-4 text-sm whitespace-nowrap {{ $class }}">
      {{ $slot }}
    </div>
  </a>
</td>
