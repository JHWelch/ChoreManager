<div class="flex justify-center">
  <div class="p-8 bg-white rounded-lg shadow-md w-96">
    <form wire:submit.prevent="save" class="space-y-4">
      <h1 class="pb-4 text-2xl">New Chore</h1>

      <x-form.input prefix="chore" name="title" />

      <x-form.input prefix="chore" name="description" />

      <x-form.select
        name="frequency_id"
        label="Frequency"
        prefix="chore"
        :options="$frequencies"
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
