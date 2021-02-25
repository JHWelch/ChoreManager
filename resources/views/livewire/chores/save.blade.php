<div class="p-12 bg-white rounded-lg shadow-md">
  <form wire:submit.prevent="save" class="space-y-4">
    <h1 class="pb-4 text-2xl">New Chore</h1>

    <x-form.text-input prefix="chore" name="title" />

    <x-form.text-input prefix="chore" name="description" />

    <x-form.select
      name="frequency_id"
      label="Frequency"
      prefix="chore"
      :options="$frequencies"
    />

    <input wire:model="chore_instance.due_date" type="date" title="Next Due Date"/>
    <input type="submit"/>
  </form>

  @if(isset($errors))
    @foreach ($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  @endif
</div>
