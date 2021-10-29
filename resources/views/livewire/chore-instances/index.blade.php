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
      class="p-1 text-sm text-purple-600 rounded-md focus:outline-none focus:ring focus:ring-purple-300 hover:underline"
      wire:click="toggleShowFutureChores"
    >
      {{ $showFutureChores ? __('Hide future chores') : __('Show future chores')}}
    </button>
  </div>

  <x-jet-confirmation-modal wire:model="showSnoozeConfirmation">
    <x-slot name="title">
      Snooze all chores due {{ Str::snakeToLabel($snoozeGroup) }}
    </x-slot>

    <x-slot name="content">
      Are you sure you want to snooze all chores due {{ Str::snakeToLabel($snoozeGroup) }} until {{ $snoozeUntil }}?
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button
          wire:click="$toggle('showSnoozeConfirmation')"
          wire:loading.attr="disabled"
        >
            Nevermind
        </x-jet-secondary-button>

        <x-jet-button
          class="ml-2"
          wire:click="snoozeGroup"
          wire:loading.attr="disabled"
        >
            Snooze
        </x-jet-button>
    </x-slot>
  </x-jet-confirmation-modal>
</div>
