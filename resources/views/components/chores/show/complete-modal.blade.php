<x-jet-dialog-modal wire:model="showCompleteForUserDialog" maxWidth="lg" overflow="">
  <x-slot name="title">
    Complete
    <span class="font-bold">
      {{ $this->chore->title }}
    </span>
  </x-slot>

  <x-slot name="content">
    <div class="mt-4">
      <x-users.avatar-dropdown
        name="user_id"
        label="Complete Chore for User"
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
    <div class="flex flex-col space-y-3 sm:space-y-0 sm:space-x-3 sm:space-x-reverse sm:flex-row-reverse">
      <x-button wire:click="customComplete">
        {{ __('Complete')}}
      </x-button>

      <x-secondary-button wire:click="$set('showCompleteForUserDialog', false)">
        {{ __('Cancel')}}
      </x-button>
    </div>
  </x-slot>
</x-jet-dialog-modal>
