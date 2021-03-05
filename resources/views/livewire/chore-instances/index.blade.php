@if ($chore_instance_groups->isEmpty())
  <div class="flex justify-center pt-8">
    <h2 class="text-2xl font-medium">{{ __('No chores here! Good job.') }}</h2>
  </div>
@else
  <nav class="relative h-full overflow-y-auto" aria-label="Chores">
    @foreach ($chore_instance_groups as $date => $chore_instances)
      @php
        $now = \Carbon\Carbon::now();
        $due_date = \Carbon\Carbon::parse($date);
        $difference = $due_date->diff($now)->days < 1
          ? 'today'
          : $due_date->diffForHumans();
      @endphp

      <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50">
        <h3>{{ $difference }}</h3>
      </div>

      <ul class="relative z-0 divide-y divide-gray-200">
        @foreach ($chore_instances as $chore_instance)
          <livewire:chore-instances.index-line :key="$loop->index" :chore="$chore_instance" />
        @endforeach
      </ul>
    @endforeach
  </nav>
@endif
