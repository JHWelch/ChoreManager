@props([
  'label'       => ucfirst($name),
  'prefix'      => '',
  'placeholder' => '',
  'name',
])

<div>
  <x-form.bare.label :name="$name" :label="$label" />

  <x-form.bare.textarea
    {{ $attributes->merge([
      'prefix'      => $prefix,
      'name'        => $name,
      'placeholder' => $placeholder,
    ]) }}
  />
</div>
