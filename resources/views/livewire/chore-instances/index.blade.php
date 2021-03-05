<nav class="relative h-full overflow-y-auto" aria-label="Directory">
  @foreach ($chore_instance_groups as $date => $chore_instances)
    @php
      $now = \Carbon\Carbon::now();
      $due_date = \Carbon\Carbon::parse($date);
      $difference = $due_date->diff($now)->days < 1
        ? 'today'
        : $due_date->diffForHumans();
    @endphp

    <div class="sticky top-0 z-10 px-6 py-1 text-sm font-medium text-gray-500 border-t border-b border-gray-200 bg-gray-50">
      <h3>{{ $difference }}</h3>
    </div>

    <ul class="relative z-0 divide-y divide-gray-200">
      @foreach ($chore_instances as $chore_instance)
        <li class="bg-white">
          <div class="relative flex items-center px-6 py-5 space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
            <div class="flex-1 min-w-0">
              <a href="#" class="focus:outline-none">
                <!-- Extend touch target to entire panel -->
                <span class="absolute inset-0" aria-hidden="true"></span>
                <p class="text-sm font-medium text-gray-900">
                  {{ $chore_instance->title }}
                </p>
                <p class="text-sm text-gray-500 truncate">
                  Co-Founder / CEO
                </p>
              </a>
            </div>
          </div>
        </li>
      @endforeach
    </ul>
  @endforeach
</nav>
