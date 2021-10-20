@props([
  'user'
])

<div class="flex items-center space-x-2">
  <div class="flex justify-start">
    <a href="#" class="flex items-center space-x-3">
      <div class="flex-shrink-0">
        <x-users.avatar :user="$user" />
      </div>

      <div class="text-sm font-medium text-gray-900">
        {{ $user->name }}
      </div>
    </a>
  </div>
</div>
