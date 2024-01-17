<div>
  <div class="flex justify-between px-2 pb-4 align-middle sm:px-0">
    <div>
      <h1 class="text-xl">Chores</h1>
    </div>

    <div class="h-5">
      <x-user-team-filter :currentfilter="$team_or_user" />
    </div>

    <x-link href="{{ route('chores.create') }}" class="">
      New <span class="hidden sm:block">&nbsp;Chore</span>
    </x-link>
  </div>

  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <x-sortable-table-header
                  table="chores"
                  column="title"
                  :sort="$sort"
                  :desc="$desc"
                />

                <x-sortable-table-header
                  table="chores"
                  column="description"
                  class="hidden lg:block"
                  :sort="$sort"
                  :desc="$desc"
                />

                <x-sortable-table-header
                  table="chores"
                  column="frequency_id"
                  label="Frequency"
                  :sort="$sort"
                  :desc="$desc"
                />

                <x-sortable-table-header
                  table="chore_instances"
                  column="due_date"
                  :sort="$sort"
                  :desc="$desc"
                />

              </tr>
            </thead>

            <tbody>
              @foreach ($chores as $chore)
                <tr class="{{ ! ($loop->index % 2) ? 'bg-white' : 'bg-gray-50'}}">
                  <x-chores.index-table-cell
                    :chore="$chore"
                    class="font-medium text-gray-900"
                  >
                    {{ $chore->title }}
                  </x-chores.index-table-cell>


                  <x-chores.index-table-cell
                    :chore="$chore"
                    class="hidden max-w-md truncate lg:block"
                  >
                    <button
                      class="text-sm text-gray-500 underline"
                      wire:click.prevent="setShowDescriptionModal({{ $chore->id }})"
                    >
                      {{ $chore->description }}
                    </button>
                  </x-chores.index-table-cell>


                  <x-chores.index-table-cell :chore="$chore">
                    {{ $chore->frequency }}
                  </x-chores.index-table-cell>


                  <x-chores.index-table-cell :chore="$chore">
                    {{
                      (is_string($chore->due_date)
                          ? $chore->due_date
                          : $chore->due_date?->format('m/d/Y')
                      ) ?? '-'
                    }}
                  </x-chores.index-table-cell>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <x-dialog-modal wire:model.live="showDescriptionModal" maxWidth="lg">
    <x-slot name="title">
      {{ $showDescriptionModalChore?->title }}
    </x-slot>

    <x-slot name="content">
      <div class="prose min-h-64">
        @markdown($showDescriptionModalChore?->description)
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-button wire:click="$set('showDescriptionModal', false)">
        Done
      </x-button>
    </x-slot>
  </x-dialog-modal>
</div>
