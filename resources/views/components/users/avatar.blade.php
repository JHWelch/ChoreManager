@props([
  'size' => 'small',
  'user'
])

@php
  $size_class = match($size) {
    'small'  => 'w-5 h-5',
    'medium' => 'w-8 h-8',
  }
@endphp

<img
  class="{{ $size_class }} rounded-full"
  src="{{ $user->profile_photo_url }}"
  alt="{{ $user->name }}'s profile photo."
  title="{{ $user->name }}"
>
