<div>
  @if (config('demo.enabled'))
    <div
      x-data="{'show': @entangle('show').live}"
      class="bg-purple-500"
      x-show="show"
      x-cloak
    >
      <div class="max-w-screen-xl px-3 py-2 mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between">
          <div class="flex items-center flex-1 w-0 min-w-0 text-white">
            <span class="flex p-2 rounded-lg" class="bg-purple-600">
              <x-icons.beaker />

            </span>

            <p class="ml-3 text-sm font-medium truncate">
              Chore Manager is running in demo mode. All changes will be reset nightly.
            </p>
          </div>

          <div class="flex flex-shrink-0 space-x-5 sm:ml-3">
            <a
              class="flex items-center space-x-2 text-sm text-white underline"
              href="{{ config('demo.repository_url') }}"
            >
              <span>Repository</span>

              <x-icons.github />
            </a>

            <button
              type="button"
              class="flex p-2 -mr-1 transition duration-150 ease-in-out rounded-md focus:outline-none sm:-mr-2"
              class="hover:bg-purple-600 focus:bg-purple-600"
              aria-label="Dismiss"
              x-on:click="show = false"
            >
              <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
