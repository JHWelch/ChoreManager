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
        <x-form.detailed-options
          title="Calendar Type"
          :options="$calendar_types"
          value="calendar_type"
        />

        <x-form.select
          name="team_id"
          label="Team"
          blankOption="Select Team"
          :options="$teams"
        />
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
</div>
