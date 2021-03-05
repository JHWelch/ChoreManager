<li class="bg-white">
  <div class="relative flex items-center px-6 py-5 space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
    <div class="flex-1 min-w-0">
      <div class="flex w-full">
        <div class="w-10/12">
          <a href="{{ route('chores.edit', ['chore' => $chore]) }}" class="focus:outline-none">
            <!-- Extend touch target to entire panel -->
            {{-- <span class="absolute inset-0" aria-hidden="true"></span> --}}
            <p class="text-sm font-medium text-gray-900">
              {{ $chore->title }}
            </p>

            <p class="text-sm text-gray-500 truncate">
              {{ $chore->description }}
            </p>
          </a>
        </div>

        <div class="flex justify-end w-2/12">
          <button
            wire:click="complete"
            class="flex items-center justify-center w-10 h-10 border-2 border-purple-300 rounded-md hover:bg-purple-200"
            >
            @if ($chore_instance->is_completed)
              <x-icons.check class="text-purple-700" />
            @endif
          </button>
        </div>
      </div>
    </div>
  </div>
</li>
