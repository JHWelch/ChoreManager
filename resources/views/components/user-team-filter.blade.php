<span class="relative z-0 inline-flex rounded-md shadow-sm">
  <button wire:click="setTeamFilter('user')" type="button" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
    <x-icons.user class="h-4" />
    <span class="ml-1">My&nbsp;Chores</span>
  </button>
  <button wire:click="setTeamFilter('team')" type="button" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
    <x-icons.group class="h-4" />
    <span class="ml-1">All&nbsp;Chores</span>
  </button>
</span>
