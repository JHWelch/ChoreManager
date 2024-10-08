<div class="flex justify-center">
  <div class="w-full p-8 bg-white rounded-lg shadow-md xl:w-10/12 dark:bg-gray-800">
    <form wire:submit="save" class="space-y-4">
      <h2 class="text-lg font-medium dark:text-gray-200">Chore</h2>

      <div class="space-y-4 lg:flex lg:space-x-4 lg:space-y-0">
        <div class="w-full space-y-4 lg:w-1/2">
          <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4 lg:flex-col lg:space-y-4 lg:space-x-0">
            <div class="md:w-1/2 lg:w-full">
              <x-form.input prefix="form" name="title" />
            </div>

            <div class="md:w-1/2 lg:w-full">
              <x-form.select
                name="chore_user_id"
                label="Owner"
                prefix="form"
                :options="$user_options"
                blankOption="Assign to Team - {{ $team }}"
              />
            </div>
          </div>
          <!-- Frequency -->
          <div class="flex flex-col">
            <x-form.bare.label prefix="form" name="frequency_id" label="Frequency" />

            <div class="flex items-center space-x-3">
              @if (! $form->isDoesNotRepeat())
                <div class="text-sm font-medium dark:text-gray-300">
                  Every
                </div>

                <x-form.bare.input
                  type="number"
                  min="1"
                  prefix="form"
                  name="frequency_interval"
                  wire:model.live="form.frequency_interval"
                />
              @endif

              <x-form.bare.select
                name="frequency_id"
                prefix="form"
                :options="$this->frequencies"
              />

              @if ($this->isShowOnButton())
                <button
                  wire:click.prevent="showDayOfSection()"
                  class="text-violet-500 font-semi-bold hover:text-violet-700"
                >
                  On
                </button>
              @endif
            </div>

            @if ($show_on)
              <div class="flex justify-between">
                <div class="flex items-center mt-2 space-x-3 text-sm">
                  @if ($form->isWeekly())
                    <label for="frequency_day_of">On</label>

                    <x-form.bare.select
                      name="frequency_day_of"
                      prefix="form"
                      :options="$this->weekly_day_of"
                    />
                  @elseif ($form->isYearly())
                    <label for="frequency_day_of">On</label>

                    <div
                      x-data="
                        {
                          date: null,
                          number: @entangle('chore.frequency_day_of').live,
                          convertDateToNumber() {
                            const d = new Date(this.date);
                            const startOfYear = new Date(new Date().getFullYear(), 0, 1);
                            this.number = Math.ceil((d - startOfYear) / 1000 / 60 / 60 / 24) + 1;
                          },
                          formatDate(date) {
                            return date.toISOString().split('T')[0];
                          },
                        }"
                      x-init="
                        date = formatDate(
                          new Date(
                            new Date().getFullYear(),
                            0,
                            number
                          )
                        )"
                    >
                      <x-form.bare.input
                        x-model="date"
                        x-on:change="convertDateToNumber()"
                        type="date"
                        min="{{ today()->startOfYear()->toDateString() }}"
                        max="{{ today()->endOfYear()->toDateString() }}"
                        class="w-8"
                      />
                    </div>

                    <span>every year</span>
                  @else
                    <label>On day</label>

                    <x-form.bare.input
                      type="number"
                      min="1"
                      max="{{ $this->max_day_of }}"
                      prefix="form"
                      name="frequency_day_of"
                      wire:model.live="chore.frequency_day_of"
                      class="w-8"
                    />

                    <span> of the {{ rtrim(lcfirst($form->frequency()->noun()), 's') }}</span>
                  @endif
                </div>

                <button
                  type="button"
                  class="text-gray-500 hover:text-gray-900 justify-self-end"
                  wire:click.prevent="hideDayOfSection"
                  aria-label="Close Day of Section"
                >
                  <x-icons.x class="w-5 h-5"/>
                </button>
              </div>
            @endif
          </div>

          @if ($form->instance_id)
            <h2 class="text-lg font-medium">Next Instance</h2>
          @endif

          <div class="flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4 lg:flex-col lg:space-y-4 lg:space-x-0">
            <div class="{{ $form->instance_id ? 'md:w-1/2 lg:w-full' : 'md:w-full' }}">
              <x-form.input type="date" prefix="form" name="due_date" label="Due Date" />
            </div>

            @if ($form->instance_id)
              <div class="md:w-1/2 lg:w-full">
                <x-form.select
                  name="instance_user_id"
                  label="Owner"
                  prefix="form"
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
              prefix="form"
              name="description"
              class="flex-grow block w-full h-48 border-gray-300 rounded-md shadow-sm resize-y dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 lg:h-96 sm:text-sm"
            />

            <a
              href="https://www.markdownguide.org/basic-syntax/"
              class="flex items-center content-end self-end space-x-2 text-xs text-gray-400 hover:underline hover:text-gray-500"
            >
              <span>This field can be styled using Markdown</span>

              <x-icons.markdown class="h-3" />
            </a>

            <x-form.bare.error prefix="form" name="description" />
          </div>
        </div>
      </div>

      <x-button>
        {{ __('Save') }}
      </x-button>
    </form>
  </div>
</div>
