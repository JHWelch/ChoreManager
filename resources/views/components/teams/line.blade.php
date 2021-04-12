@props([
  'team'
])

<div class="flex items-center space-x-2">
  <div class="flex justify-start">
    <a href="#" class="flex items-center space-x-3">
      <div class="flex-shrink-0">
        <x-icons.group class="w-5 h-5 text-gray-500" />
      </div>

      <div class="text-sm font-medium text-gray-900">
        {{ $team }}
      </div>
    </a>
  </div>
</div>
