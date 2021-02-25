<div class="p-12 bg-white rounded-lg shadow-md">
  <form wire:submit.prevent="save">
    <h1 class="pb-4 text-2xl">New Chore</h1>
    <div>
      <label for="title">Title</label>
      <input wire:model="chore.title" id="title" type="text">
    </div>
    <div>
      <label for="description">Description</label>
      <input wire:model="chore.description" id="description" type="text">
    </div>
    <select wire:model="chore.frequency_id">
      @foreach (\App\Models\Chore::FREQUENCIES as $key => $frequencyOption)
        <option wire:key="$key" value={{ $key }}>{{ $frequencyOption }}</option>
      @endforeach
    </select>
    <input wire:model="chore_instance.due_date" type="date" title="Next Due Date"/>
    <input type="submit"/>
  </form>

  @if(isset($errors))
    @foreach ($errors->all() as $error)
      <div>{{ $error }}</div>
    @endforeach
  @endif
</div>
