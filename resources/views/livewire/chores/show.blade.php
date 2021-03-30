<div>
  <div class="p-8 mt-4 bg-white shadow-md rounded-xl">
    <div class="space-y-2">
      <h1 class="text-3xl">
        {{ $chore->title }}
      </h1>

      <div class="text-gray-600">
        {{ $chore->frequency }}
      </div>

      <a href="{{ route('chores.edit', $chore) }}" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
        <x-icons.pencil class="w-5 h-5 mr-2 -ml-1 text-gray-400"/>

        <span>Edit</span>
      </a>

      <div class="prose">
        {{ $chore->description }}
      </div>
    </div>
  </div>
</div>
