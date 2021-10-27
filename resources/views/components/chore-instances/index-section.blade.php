@props([
  'group',
  'choreInstanceDateGroups',
])

@php
  $outer_class = match($group) {
    'past_due' => 'border border-red-300 bg-red-100',
    'today' => 'border border-purple-300 bg-purple-100',
    default => 'bg-white'
  }
@endphp

<div wire:key="outer-{{ $group }}" class="mb-4 shadow sm:rounded-lg pb-1 {{ $outer_class }}">
  <div class="flex justify-center w-full px-3 py-2">
    <h2 class="text-xl ">{{ Str::snakeToLabel($group) }}</h2>
  </div>

  @foreach ($choreInstanceDateGroups as $date_group => $chore_instances)
    <div wire:key="inner-{{ Str::snake($date_group, '-') }}">
      @if ($date_group !== 'today')
        <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50">
          <h3>{{ $date_group }}</h3>
        </div>
      @endif

      <ul class="relative divide-y divide-gray-200">
        @foreach ($chore_instances as $chore_instance)
          <livewire:chore-instances.index-line :wire:key="$chore_instance['chore_instance_id']" :chore="$chore_instance" />
        @endforeach
      </ul>
    </div>
  @endforeach
</div>
