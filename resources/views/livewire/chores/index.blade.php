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
              <!-- Odd row -->
              @foreach ($chores as $chore)
                <tr onclick="window.location='{{ route('chores.show', ['chore' => $chore]) }}';" class="cursor-pointer {{ ! ($loop->index % 2) ? 'bg-white' : 'bg-gray-50'}}">
                  <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                    {{ $chore->title }}
                  </td>

                  <td class="hidden max-w-md px-6 py-4 truncate lg:block whitespace-nowrap">
                    <button
                      class="text-sm text-gray-500 underline"
                      wire:click.stop="setShowDescriptionModal({{ $chore->id }})"
                    >
                      {{ $chore->description }}
                    </button>
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                    {{ $chore->frequency }}
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                    {{ $chore->due_date?->format('n/j/Y') ?? '-' }}
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <x-jet-dialog-modal wire:model="showDescriptionModal" maxWidth="lg">
    <x-slot name="title">
      {{ $showDescriptionModalChore?->title }}
    </x-slot>

    <x-slot name="content">
      <div class="prose min-h-64">
        @markdown($showDescriptionModalChore?->description)
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-jet-button wire:click="$set('showDescriptionModal', false)">
        Done
      </x-jet-button>
    </x-slot>
  </x-jet-dialog-modal>
</div>
