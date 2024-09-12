@props([
  'group',
  'choreInstanceDateGroups',
])

@php
  $outer_class = match($group) {
    'past_due' => 'border border-red-300 bg-red-100 dark:bg-red-900 dark:border-red-800',
    'today' => 'border border-purple-300 bg-purple-100 dark:bg-purple-900 dark:border-purple-800',
    default => 'bg-white dark:bg-gray-800'
  }
@endphp

<div wire:key="outer-{{ $group }}" class="mb-4 shadow sm:rounded-lg pb-1 {{ $outer_class }}">
  <div class="grid w-full grid-cols-3 gap-4 px-3 py-2">
    <div></div>
    <h2 class="flex items-center justify-center text-xl dark:text-gray-100">
      {{ Str::snakeToLabel($group) }}
    </h2>

    <div class="relative flex justify-end">
      @if ($group === 'past_due' || $group === 'today')
        <x-snooze-button
          size="medium"
          right="-right-4"
          top="-top-12"
          snoozeUntilTomorrow="$dispatch('openModal', {
            component: 'chore-instances.modals.snooze-group',
            arguments: {
              group: '{{ $group }}',
              until: 'tomorrow',
            }
          })"
          snoozeUntilWeekend="$dispatch('openModal', {
            component: 'chore-instances.modals.snooze-group',
            arguments: {
              group: '{{ $group }}',
              until: 'the weekend',
            }
          })"
        />
      @endif
    </div>
  </div>

  @foreach ($choreInstanceDateGroups as $date_group => $chore_instances)
    <div wire:key="inner-{{ Str::snake($date_group, '-') }}">
      @if ($date_group !== 'today')
        <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
          <h3>{{ $date_group }}</h3>
        </div>
      @endif

      <ul class="relative divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($chore_instances as $chore_instance)
          <livewire:chore-instances.index-line
            :key="$date_group.'-index-line-'.$chore_instance['chore_instance_id']"
            :chore="$chore_instance"
          />
        @endforeach
      </ul>
    </div>
  @endforeach
</div>
