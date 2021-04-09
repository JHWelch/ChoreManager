@props([
  'user'
])

<div class="flex items-center space-x-2">
  <div class="flex justify-start">
    @if ($user)
      <a href="#" class="flex items-center space-x-3">
        <div class="flex-shrink-0">
          <img
            class="w-5 h-5 rounded-full"
            src="{{ $user->profile_photo_url }}"
            alt="{{ $user->name }}'s profile photo."
          >
        </div>

        <div class="text-sm font-medium text-gray-900">
          {{ $user->name }}
        </div>
      @endif
    </a>
  </div>
</div>
