<div class="max-h-full">
  <div class="flex justify-between px-2 pb-4 align-middle sm:px-0">
    <div>
      <h1 class="text-xl dark:text-gray-200">Upcoming Chores</h1>
    </div>

    <div class="h-5">
      <x-user-team-filter :currentfilter="$team_or_user" />
    </div>

    <x-link href="{{ route('chores.create') }}" class="">
      New <span class="hidden sm:block">&nbsp;Chore</span>
    </x-link>
  </div>

  @if ($choreInstanceGroups->isEmpty())
    <div class="flex justify-center p-8">
      <h2 class="text-2xl font-medium">{{ __('All done for today') }}</h2>
    </div>
  @else
    <nav class="relative h-full" aria-label="Chores">
      @foreach($choreInstanceGroups as $group => $choreInstanceDateGroups)
        <x-chore-instances.index-section
          :group="$group"
          :choreInstanceDateGroups="$choreInstanceDateGroups"
        />
      @endforeach
    </nav>
  @endif

  <div class="flex justify-center w-full align-middle">
    <button
      class="p-1 text-sm text-violet-600 rounded-md focus:outline-none focus:ring focus:ring-violet-300 hover:underline"
      wire:click="toggleShowFutureChores"
    >
      {{ $showFutureChores ? __('Hide future chores') : __('Show future chores')}}
    </button>
  </div>
</div>
