@props([
  'label'  => ucfirst($name),
  'prefix' => null,
  'name',
  'blankOption' => false,
])

<div x-data="{
  users: @js($this->users),
  selected: @entangle('chore.user_id').defer,
  open: false,
  selectedUser: function () {
    return this.users.find(user => user.id === this.selected);
  },
  setUser: function (userId) {
    this.selected = userId;
    this.open = false;
  },
  highlightClass: function (isActive) {
    return isActive ? 'bg-purple-600 text-white' : 'text-gray-900';
  },
  checkColor: function (isActive) {
    return isActive ? 'text-white' : 'text-gray-900';
  }
  {{-- selected: @entangle('{{ $prefix ? $prefix . '.' . $name : $name }}') --}}
}">
  <x-form.bare.label :name="$name" :label="$label" />

  <div
    x-menu
    x-model="open"
    class="relative mt-2"
  >
    <button
      x-menu:button
      type="button"
      class="relative w-full cursor-default rounded-md bg-white py-1.5 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 sm:text-sm sm:leading-6"
      aria-haspopup="listbox"
      aria-expanded="true"
      aria-labelledby="listbox-label"
    >
      <span class="flex items-center">
        <img
          :src="selectedUser().profile_photo_url"
          alt=""
          class="flex-shrink-0 w-5 h-5 rounded-full"
        >
        <span
          x-text="selectedUser().name"
          class="block ml-3 truncate"
        ></span>
      </span>

      <span class="absolute inset-y-0 right-0 flex items-center pr-2 ml-3 pointer-events-none">
        <x-icons.menu-arrows />
      </span>
    </button>

    <!--
      Select popover, show/hide based on select state.

      Entering: ""
        From: ""
        To: ""
      Leaving: "transition ease-in duration-100"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <ul
      x-menu:items
      class="absolute z-10 w-full py-1 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg max-h-56 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
      tabindex="-1"
      role="listbox"
      aria-labelledby="listbox-label"
      aria-activedescendant="listbox-option-3"
    >
      <template x-for="user in users">
        <li
          x-menu:item
          x-on:click="setUser(user.id)"
          id="listbox-option-0"
          role="option"
          :class="'relative py-2 pl-3 cursor-default select-none pr-9 ' + highlightClass($menuItem.isActive)"
        >
          <div class="flex items-center">
            <img
              :src="user.profile_photo_url"
              alt=""
              class="flex-shrink-0 w-5 h-5 rounded-full"
            >

            <span
              :class="'block ml-3 truncate' + (selected === user.id ? ' font-semibold' : ' font-normal')"
              x-text="user.name"
            ></span>
          </div>

          <span
            x-show="selected === user.id"
            :class="'absolute inset-y-0 right-0 flex items-center pr-4 ' + checkColor($menuItem.isActive)"
          >
            <x-icons.check />
          </span>
        </li>
      </template>
    </ul>
  </div>
</div>
