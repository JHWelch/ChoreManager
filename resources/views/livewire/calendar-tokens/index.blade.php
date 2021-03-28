@push('head')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
@endpush

<div>
  <x-jet-form-section submit="addCalendarLink">
    <x-slot name="title">
      {{ __('Create iCalendar Link') }}
    </x-slot>

    <x-slot name="description">
      {{ __('Creates an iCalendar link that can be loaded in your favorite calendar application.') }}
    </x-slot>

    <x-slot name="form">
      <div class="col-span-8 space-y-4 lg:col-span-4">
        <x-form.input
          prefix="calendar_token"
          name="name"
          placeholder="Optional"
        />


        <x-form.detailed-options
          title="Calendar Type"
          :options="$calendar_types"
          value="calendar_type"
        />

        @if ($calendar_type === 'team')
          <x-form.select
            prefix="calendar_token"
            name="team_id"
            label="Team"
            blankOption="Select Team"
            :options="$teams"
          />
        @endif
      </div>
    </x-slot>

    <x-slot name="actions">
      <x-jet-action-message class="mr-3" on="created">
        {{ __('Created.') }}
      </x-jet-action-message>

      <x-jet-button>
        {{ __('Create') }}
      </x-jet-button>
    </x-slot>
  </x-jet-form-section>

  @if ($calendar_tokens->isNotEmpty())
    <x-jet-section-border />

    <div class="mt-10 sm:mt-0">
      <x-jet-action-section>
        <x-slot name="title">
          {{ __('Manage Calendar Links') }}
        </x-slot>

        <x-slot name="description">
          {{ __('Get your link for existing calendars or delete ones you no longer need.') }}
        </x-slot>

        <x-slot name="content">
          <div class="space-y-6">
            @foreach ($calendar_tokens as $token)
              <div class="flex items-center justify-between">
                <div class="flex flex-col">
                  <div class="text-lg">{{ $token->display_name }}</div>
                  <div class="text-sm text-gray-500">
                    {{ $token->full_type_name }}
                  </div>
                </div>

                <div class="flex items-center">
                  <button
                    id="url-{{ $loop->index }}"
                    data-clipboard-action="copy"
                    data-clipboard-target="#url-{{ $loop->index }}"
                    class="text-xs text-gray-400 underline cursor-pointer url-link">
                    {{ $token->url }}
                  </button>

                  <button class="ml-6 text-sm text-red-500 cursor-pointer"
                    wire:click="deleteToken({{ $token->id }})">
                    {{ __('Delete') }}
                  </button>
                </div>
              </div>
            @endforeach
          </div>
        </x-slot>
      </x-jet-action-section>
    </div>
  @endif

  <script>
    new ClipboardJS('.url-link');
  </script>
</div>
