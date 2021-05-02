<div class="max-h-full">
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

        <div wire:key="outer-{{ $group }}" class="mb-4 shadow sm:rounded-lg pb-1 {{ $outer_class }}">
          <div class="flex justify-center w-full px-3 py-2">
            <h2 class="text-xl ">{{ Str::snakeToLabel($group) }}</h2>
          </div>

          @foreach ($chore_instance_date_groups as $group => $chore_instances)
            <div wire:key="inner-{{ $group }}">
              @if ($group !== 'today')
                <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50">
                  <h3>{{ $group }}</h3>
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

  <div class="flex justify-center w-full align-middle">
    <button
      class="p-1 text-sm text-indigo-600 rounded-md focus:outline-none focus:ring focus:ring-indigo-300 hover:underline"
      wire:click="toggleShowFutureChores"
    >
      {{ $showFutureChores ? __('Hide future chores') : __('Hide future chores')}}
    </button>
  </div>
</div>
