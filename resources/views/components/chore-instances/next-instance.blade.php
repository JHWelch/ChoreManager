@props([
  'instance' => null,
  'class' => ''
])

@if ($instance)
  <div class="{{ $class }}">
    <h2 class="text-sm font-medium text-gray-500">Next Instance</h2>

    <div class="mt-3 space-y-5">
      <div class="flex items-center space-x-2">
        <x-icons.calendar solid="true" class="w-5 h-5 text-gray-400" />

        <span class="text-sm font-medium text-gray-900">
          Due on

          <time datetime="{{ $instance->due_date->toDateString() }}">
            {{ $instance->due_date->toFormattedDateString() }}
          </time>
        </span>
      </div>

      <x-users.avatar-line :user="$instance->user" />
    </div>
  </div>
@endif
