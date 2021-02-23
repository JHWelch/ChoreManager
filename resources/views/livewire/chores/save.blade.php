<div class="p-20">
    <div class="p-12 bg-white rounded-lg shadow-md">
        <form wire:submit.prevent="save">
            <h1 class="pb-4 text-2xl">New Chore</h1>
            <div>
                <label for="title">Title</label>
                <input wire:model="title" id="title" type="text">
            </div>
            <div>
                <label for="description">Description</label>
                <input wire:model="description" id="description" type="text">
            </div>
            <select wire:model="frequency">
                @foreach (\App\Models\Chore::FREQUENCIES as $key => $frequencyOption)
                    <option wire:key="$key" value={{$key}}>{{ $frequencyOption }}</option>
                @endforeach
            </select>
            <input type="submit"/>
        </form>

        @if(isset($errors))
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        @endif
    </div>
</div>
