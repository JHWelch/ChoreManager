<li
  x-data="{ show: true }"
  x-show="show"
  x-description="Chore Line. When completed slides out with a transition."
  x-transition:leave="transition ease-in-out duration-500"
  x-transition:leave-start="translate-x-0"
  x-transition:leave-end="translate-x-full"
  x-on:checked="
    show=false;
    setTimeout($wire.complete, 500);
  "
  class="bg-white dark:bg-gray-800"
>
  <a
    href="{{ route('chores.show', ['chore' => $chore]) }}"
    class="relative flex items-center px-6 py-5 space-x-3 hover:bg-gray-50 dark:hover:bg-gray-900 focus-within:ring-2 focus-within:ring-inset focus-within:ring-violet-500"
  >
    <div class="flex-1 min-w-0">
      <div class="flex w-full">
        <div class="w-8/12">
          <div class="flex flex-row items-center space-x-3 focus:outline-none">
            {{-- <!-- Extend touch target to entire panel --> --}}
            {{-- <span class="absolute inset-0" aria-hidden="true"></span> --}}

            {{-- Disabled row profile photos, was causing bug --}}
            {{-- <x-users.avatar :user="$chore->user" size="medium" /> --}}

            <div class="flex flex-col">
              <p class="text-sm font-medium text-gray-900 dark:text-gray-300">
                {{ $chore->title }}
              </p>

              <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                {{ $chore->frequency }}
              </p>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-end w-4/12 space-x-4">
          <x-snooze-button />

          <x-chore-checkbox />
        </div>
      </div>
    </div>
  </a>
</li>
