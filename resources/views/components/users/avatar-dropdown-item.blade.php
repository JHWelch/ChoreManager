@props([
  'userVar' => 'user',
])

<li
  x-menu:item
  x-on:click="setUser({{ $userVar }}.id)"
  :id="'user-' + {{ $userVar }}.id"
  role="option"
  :class="'relative py-2 pl-3 cursor-default select-none pr-9 ' + highlightClass($menuItem.isActive)"
>
  <div class="flex items-center">
    <img
      :src="{{ $userVar }}.profile_photo_url"
      alt=""
      class="flex-shrink-0 w-5 h-5 rounded-full"
    >

    <span
      :class="'block ml-3 truncate' + (selected === {{ $userVar }}.id ? ' font-semibold' : ' font-normal')"
      x-text="{{ $userVar }}.name"
    ></span>
  </div>

  <span
    x-show="selected === {{ $userVar }}.id"
    :class="'absolute inset-y-0 right-0 flex items-center pr-4 ' + checkColor($menuItem.isActive)"
  >
    <x-icons.check />
  </span>
</li>
