@props([
  'label'       => ucfirst($name),
  'prefix'      => '',
  'placeholder' => '',
  'type'        => 'text',
  'name',
])

<div>
  <x-form.bare.label :name="$name" :label="$label" />

  <x-form.bare.input
    {{ $attributes->merge([
      'prefix'      => $prefix,
      'name'        => $name,
      'type'        => $type,
      'placeholder' => $placeholder,
    ]) }}
  />

  <x-form.bare.error :prefix="$prefix" :name="$name" />
</div>
