@props([
  'prefix' => '',
  'name',
])

<div>
  @if($error = $errors->first($prefix . '.' . $name))
    <p class="mt-1 text-sm text-red-400" data-error>{{ $error }}</p>
  @endif
</div>
