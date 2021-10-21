<main class="relative flex-1 min-h-screen mb-4 overflow-y-auto bg-white shadow focus:outline-none sm:rounded-lg" tabindex="-1">
  <div class="py-8 xl:py-10">
    <div class="max-w-3xl px-4 mx-auto sm:px-6 lg:px-8 xl:max-w-5xl xl:grid xl:grid-cols-3">
      <div class="xl:col-span-2 xl:pr-8 xl:border-r xl:border-gray-200">
        <div>
          <div>
            <div class="md:flex md:items-center md:justify-between md:space-x-4 xl:border-b xl:pb-6">
              <div class="flex justify-between">
                <div>
                  <h1 class="text-2xl font-bold text-gray-900">{{ $chore->title }}</h1>

                  <p class="mt-2 text-sm text-gray-600">
                    {{ $chore->frequency->toPrefixedString('Repeats') }}
                  </p>
                </div>

                <x-chores.show-menu class="md:hidden"/>
              </div>

              <div class="flex items-center mt-4 space-x-3 md:mt-0">
                <a
                  href="{{ route('chores.edit', $chore) }}"
                  class="inline-flex justify-center px-4 py-2 text-sm font-medium text-purple-700 bg-white border border-purple-300 rounded-md shadow-sm hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-900"
                >
                  <x-icons.pencil class="w-5 h-5 mr-2 -ml-1 text-purple-400"/>

                  <span>Edit</span>
                </a>

                <button
                  wire:click="complete"
                  type="button"
                  class="inline-flex justify-center px-4 py-2 text-sm font-medium text-purple-700 bg-white border border-purple-300 rounded-md shadow-sm hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-900"
                >
                  <x-icons.check-circle class="w-5 h-5 mr-2 -ml-1 text-purple-400" />

                  <span>Complete</span>
                </button>

                <x-chores.show-menu class="hidden md:block"/>
              </div>
            </div>

            <aside class="mt-6 xl:hidden">
              <x-chore-instances.next-instance :instance="$chore_instance" class="mb-6" />

              <div class="{{ $chore_instance ? 'border-t py-6' : 'pb-6' }} space-y-8 border-b border-gray-200">
                <div>
                  <h2 class="text-sm font-medium text-gray-500">Owner</h2>

                  <div class="mt-3">
                    @if ($chore->user)
                      <x-users.avatar-line :user="$chore->user" />
                    @else
                      <x-teams.line :team="$chore->team->name" />
                    @endif
                  </div>
                </div>
              </div>
            </aside>
            <div class="py-3 xl:pt-6 xl:pb-0">
              <h2 class="sr-only">Description</h2>

              <div class="prose max-w-none">
                @markdown($chore->description)
              </div>
            </div>
          </div>
        </div>
        <section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
          <div>
            <div class="divide-y divide-gray-200">
              <div class="pb-4">
                <h2 id="activity-title" class="text-lg font-medium text-gray-900">Activity</h2>
              </div>

              <div class="pt-6">
                <!-- Activity feed-->
                <div class="flow-root">
                  <ul class="-mb-8">
                    @foreach ($past_chore_instances as $past_chore_instance)
                      <li>
                        <div class="relative pb-8">
                          <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                          <div class="relative flex items-start space-x-3">
                            <div class="relative">
                              <img
                                class="flex items-center justify-center w-10 h-10 bg-gray-400 rounded-full ring-8 ring-white"
                                src="{{ $past_chore_instance->completedBy->profile_photo_url }}"
                                alt="{{ $past_chore_instance->completedBy->name }}'s profile photo."
                              >

                              <span class="absolute -bottom-0.5 -right-1 bg-white rounded-tl px-0.5 py-px opacity-80">
                                <x-icons.check class="w-5 h-5 text-gray-600" />
                              </span>
                            </div>

                            <div class="flex-1 min-w-0 py-0">
                              <div class="text-sm leading-8 text-gray-500">
                                <span class="mr-0.5">
                                  <a href="#" class="font-medium text-gray-900">
                                    {{ $past_chore_instance->completedBy->name }}
                                  </a>

                                  completed chore
                                </span>

                                <span class="whitespace-nowrap">
                                  {{ $past_chore_instance->completed_date->diffDaysForHumans() }}
                                </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    @endforeach

                    <li>
                      <div class="relative pb-8">
                        <div class="relative flex items-start space-x-3">
                          <div>
                            <div class="relative px-1">
                              <div class="flex items-center justify-center w-8 h-8 bg-gray-100 rounded-full ring-8 ring-white">
                                <x-icons.pencil-paper class="w-5 h-5 text-gray-500" />
                              </div>
                            </div>
                          </div>

                          <div class="min-w-0 flex-1 py-1.5">
                            <div class="text-sm text-gray-500">
                              <span class="mr-0.5">
                                Chore Created
                              </span>

                              <span class="whitespace-nowrap">
                                {{ $chore->created_at->diffForHumans() }}
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>

      <aside class="hidden xl:block xl:pl-8">
        <x-chore-instances.next-instance :instance="$chore_instance" class="mb-6" />

        <div class="{{ $chore_instance ? 'border-t py-6' : '' }} space-y-8 border-gray-200">
          <div>
            <h2 class="text-sm font-medium text-gray-500">Owner</h2>

            <div class="mt-3">
              @if ($chore->user)
                <x-users.avatar-line :user="$chore->user" />
              @else
                <x-teams.line :team="$chore->team->name" />
              @endif
            </div>
          </div>
        </div>
      </aside>
    </div>
  </div>

  <x-jet-confirmation-modal wire:model="showDeleteConfirmation">
    <x-slot name="title">
        Delete "{{ $chore->title }}"
    </x-slot>

    <x-slot name="content">
        Are you sure you want to delete this chore? All completion history will be permanently deleted.
      </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('showDeleteConfirmation')" wire:loading.attr="disabled">
            Nevermind
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
            Delete
        </x-jet-danger-button>
    </x-slot>
  </x-jet-confirmation-modal>

  <x-jet-dialog-modal wire:model="showCompleteForUserDialog" maxWidth="lg">
    <x-slot name="title">
      {{ __('Custom Chore Completion')}}
    </x-slot>

    <x-slot name="content">
      {{ __('Choose another user on your team to mark the chore completed by them and/or pick a date in the past for the chore to be completed.') }}

      <div class="mt-4">
        <x-form.select
          name="user_id"
          label="Complete Chore for User"
          blankOption="{{ Auth::user()->name }}"
          :options="$this->user_options"
        />
      </div>

      <div class="mt-4">
        <x-form.input
          name="completed_date"
          type="date"
          label="Complete Chore on Date"
          max="{{ today()->toDateString() }}"
        />
      </div>
    </x-slot>

    <x-slot name="footer">
      <x-jet-secondary-button wire:click="$set('showCompleteForUserDialog', false)">
        {{ __('Cancel')}}
      </x-jet-secondary-button>

      <x-jet-button wire:click="customComplete">
        {{ __('Complete')}}
      </x-jet-button>
    </x-slot>
  </x-jet-dialog-modal>
</main>

