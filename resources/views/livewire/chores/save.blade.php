<div class="flex justify-center">
  <div class="p-8 bg-white rounded-lg shadow-md w-96">
    <form wire:submit.prevent="save" class="space-y-4">
      <x-form.input prefix="chore" name="title" />

      <x-form.input prefix="chore" name="description" />

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

      <x-form.select
        name="user_id"
        label="Owner"
        prefix="chore"
        :options="$user_options"
      />

      <x-form.input type="date" prefix="chore_instance" name="due_date" label="Next Due Date" />

      <x-jet-button>
        {{ __('Save') }}
      </x-jet-button>
    </form>

    @if(isset($errors))
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    @endif
  </div>
</div>
