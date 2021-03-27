@props([
  'column',
  'table',
  'sort',
  'desc',
  'label' => Str::of(__(Str::snakeToTitle($column)))->replace(' ', '&nbsp'),
  'class' => ''
])

@php($sort_by = "{$table}.{$column}")

<th
  wire:click="sortBy('{{ $sort_by }}')"
  scope="col"
  class="{{ $class }} px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase cursor-pointer"
>
  <div class="flex">
    {!! $label !!}

    @if ($sort_by === $sort)
      @if ($desc)
        <x-icons.chevron-down class="w-4 h-4 text-gray-400"/>
      @else
        <x-icons.chevron-up class="w-4 h-4 text-gray-400" />
      @endif
    @endif
  </div>
</th>
