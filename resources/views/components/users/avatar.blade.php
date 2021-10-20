@props([
  'user'
])

<img
  class="w-5 h-5 rounded-full"
  src="{{ $user->profile_photo_url }}"
  alt="{{ $user->name }}'s profile photo."
  title="{{ $user->name }}"
>
