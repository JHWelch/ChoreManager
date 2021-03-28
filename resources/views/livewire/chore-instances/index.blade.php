<div class="h-full">
  <div class="flex justify-between px-2 pb-4 align-middle sm:px-0">
    <div>
      <h1 class="text-xl">Upcoming Chores</h1>
    </div>

    <div class="h-5">
      <x-user-team-filter :currentfilter="$team_or_user" />
    </div>

    <x-link href="{{ route('chores.create') }}" class="">
      New <span class="hidden sm:block">&nbsp;Chore</span>
    </x-link>
  </div>

  @if ($chore_instance_groups->isEmpty())
    <div class="flex justify-center pt-8">
      <h2 class="text-2xl font-medium">{{ __('No chores here! Good job.') }}</h2>
    </div>
  @else
    <nav class="relative h-full overflow-y-auto" aria-label="Chores">
      @foreach($chore_instance_groups as $group => $chore_instance_date_groups)
        @php
          $outer_class = match($group) {
            'past_due' => 'border border-red-300 bg-red-100',
            'today' => 'border border-indigo-300 bg-indigo-100',
            default => 'bg-white'
          }
        @endphp

        <div wire:key="{{ $group }}" class="mb-4 shadow sm:rounded-lg {{ $outer_class }}">
          <div class="flex justify-center w-full px-3 py-2">
            <h2 class="text-xl ">{{ Str::snakeToLabel($group) }}</h2>
          </div>

          @foreach ($chore_instance_date_groups as $date => $chore_instances)
            @php
              $due_date = \Carbon\Carbon::parse($date)->startOfDay();
              $difference = ucfirst($due_date->diffForHumans(
                today(),
                [
                  'options' => \Carbon\CarbonInterface::ONE_DAY_WORDS,
                  'syntax'  => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW
                ]
              ));
            @endphp

            <div wire:key="{{ $date }}">
              @if ($group !== 'today')
                <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50">
                  <h3>{{ $difference }}</h3>
                </div>
              @endif

              <ul class="relative divide-y divide-gray-200">
                @foreach ($chore_instances as $chore_instance)
                  <livewire:chore-instances.index-line :key="$chore_instance['chore_instance_id']" :chore="$chore_instance" />
                @endforeach
              </ul>
            </div>
          @endforeach
        </div>
      @endforeach
    </nav>
  @endif
</div>
