<span class="relative z-0 inline-flex rounded-md shadow-sm" aria-label="Team User Filter">
  <x-button-group-button
    click="setTeamFilter('user')"
    :selected="$currentfilter === 'user'"
    position="left"
    key="user"
  >
    <x-icons.user class="h-4" />

    <span class="hidden ml-1 sm:block">My&nbsp;Chores</span>
  </x-button-group-button>
  <x-button-group-button
    click="setTeamFilter('team')"
    :selected="$currentfilter === 'team'"
    position="right"
    key="team"
  >
    <x-icons.group class="h-4" />

    <span class="hidden ml-1 sm:block">All&nbsp;Chores</span>
  </x-button-group-button>
</span>
