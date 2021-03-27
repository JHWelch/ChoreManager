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
                <th wire:click="sortBy('title')"  scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                  {{ __('Title') }}
                </th>

                <th scope="col" class="hidden px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase sm:block">
                  {{ __('Description') }}
                </th>

                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                  {{ __('Frequency') }}
                </th>

                <th scope="col" class="flex px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                  <span class="hidden sm:block">{{ __('Next') }}&nbsp;</span>
                  {{ __('Due') }}
                  <span class="hidden sm:block">&nbsp;{{ __('Date') }}</span>
                </th>
              </tr>
            </thead>
            <tbody>
              <!-- Odd row -->
              @foreach ($chores as $chore)
                <tr onclick="window.location='{{ route('chores.edit', ['chore' => $chore]) }}';" class="cursor-pointer {{ ! ($loop->index % 2) ? 'bg-white' : 'bg-gray-50'}}">
                  <td class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap">
                    {{ $chore->title }}
                  </td>

                  <td class="hidden px-6 py-4 text-sm text-gray-500 whitespace-nowrap sm:block">
                    {{ $chore->description }}
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
</div>
