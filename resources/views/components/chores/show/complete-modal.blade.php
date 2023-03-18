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
