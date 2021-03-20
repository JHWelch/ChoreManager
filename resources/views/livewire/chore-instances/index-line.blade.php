<li
  x-data="{ show: true }"
  x-show="show"
  x-description="Chore Line. When completed slides out with a transition."
  x-transition:leave="transform transition ease-in-out duration-500"
  x-transition:leave-start="translate-x-0"
  x-transition:leave-end="translate-x-full"
  class="bg-white"
>
  <div class="relative flex items-center px-6 py-5 space-x-3 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500">
    <div class="flex-1 min-w-0">
      <div class="flex w-full">
        <div class="w-8/12">
          <a href="{{ route('chores.edit', ['chore' => $chore]) }}" class="focus:outline-none">
            <!-- Extend touch target to entire panel -->
            {{-- <span class="absolute inset-0" aria-hidden="true"></span> --}}
            <p class="text-sm font-medium text-gray-900">
              {{ $chore->title }}
            </p>

            <p class="text-sm text-gray-500 truncate">
              {{ $chore->frequency }}
            </p>
          </a>
        </div>

        <div class="flex items-center justify-end w-4/12 space-x-4">
          <x-snooze-button />

          <button
            @click="
              show=false;
              setTimeout($wire.complete, 500);
            "
            @if ($chore_instance->is_completed)
              disabled
            @endif
            class="flex items-center justify-center border-4 border-indigo-400 rounded-md w-9 h-9 hover:bg-indigo-200"
            >
            @if ($chore_instance->is_completed)
              <x-icons.check class="text-indigo-700" />
            @endif
          </button>
        </div>
      </div>
    </div>
  </div>
</li>
