@props([
  'id' => null,
  'maxWidth' => null,
  'overflow' => 'overflow-hidden'
])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" :overflow="$overflow" {{ $attributes }}>
  <div class="px-6 py-4">
    <div class="text-lg">
      {{ $title }}
    </div>

    <div class="mt-4">
      {{ $content }}
    </div>
  </div>

  <div class="px-6 py-4 text-right bg-gray-100 rounded-lg">
    {{ $footer }}
  </div>
</x-jet-modal>
