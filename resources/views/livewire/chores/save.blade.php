<div class="flex justify-center">
  <div class="w-full p-8 bg-white rounded-lg shadow-md xl:w-10/12">
    <form wire:submit.prevent="save" class="space-y-4">
      <h2 class="text-lg font-medium">Chore</h2>

      <div class="space-y-4 lg:flex lg:space-x-4 lg:space-y-0">
        <div class="w-full space-y-4 lg:w-1/2">
          <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4 lg:flex-col lg:space-y-4 lg:space-x-0">
            <div class="md:w-1/2 lg:w-full">
              <x-form.input prefix="chore" name="title" />
            </div>

            <div class="md:w-1/2 lg:w-full">
              <x-form.select
                name="user_id"
                label="Owner"
                prefix="chore"
                :options="$user_options"
                blankOption="Assign to Team - {{ $team }}"
              />
            </div>
          </div>
          <!-- Frequency -->
          <div class="flex flex-col">
            <x-form.bare.label prefix="chore" name="frequency_id" label="Frequency" />

            <div class="flex items-center space-x-2 justify-beween">
              @if ($chore->frequency_id != 0)
                <div class="w-1/3 text-sm font-medium">
                  Every
                </div>

                <x-form.bare.input
                  type="number"
                  min="1"
                  prefix="chore"
                  name="frequency_interval"
                  wire:model="chore.frequency_interval"
                />
              @endif

              <x-form.bare.select
                name="frequency_id"
                prefix="chore"
                :options="$frequencies"
              />
            </div>
          </div>

          @if ($chore_instance->exists)
            <h2 class="text-lg font-medium">Next Instance</h2>
          @endif

          <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4 lg:flex-col lg:space-y-4 lg:space-x-0">
            <div class="{{ $chore_instance->exists ? 'md:w-1/2 lg:w-full' : 'md:w-full' }}">
              <x-form.input type="date" prefix="chore_instance" name="due_date" label="Due Date" />
            </div>

            @if ($chore_instance->exists)
              <div class="md:w-1/2 lg:w-full">
                <x-form.select
                  name="user_id"
                  label="Owner"
                  prefix="chore_instance"
                  :options="$user_options"
                />
              </div>
            @endif
          </div>
        </div>

        <div class="flex flex-col h-full lg:w-1/2">
          <div class="flex flex-col h-full space-y-1">
            <x-form.bare.label name="description" />

            <x-form.bare.textarea
              prefix="chore"
              name="description"
              class="flex-grow block w-full border-gray-300 rounded-md shadow-sm resize-y h-96 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            />

            <a
              href="https://www.markdownguide.org/basic-syntax/"
              class="flex items-center content-end self-end space-x-2 text-xs text-gray-400 hover:underline hover:text-gray-500"
            >
              <span>This field can be styled using Markdown</span>

              <x-icons.markdown class="h-3" />
            </a>

            <x-form.bare.error prefix="chore" name="description" />
          </div>
        </div>
      </div>

      <x-jet-button>
        {{ __('Save') }}
      </x-jet-button>
    </form>
  </div>
</div>
