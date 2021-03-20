@props([
  'label'  => ucfirst($name),
  'prefix' => '',
  'name',
  'blankOption' => false,
  'options'     => [],
  'disabled'    => false,
])

<div>
  <x-form.bare.label :name="$name" :label="$label" />

  <x-form.bare.select
    :prefix="$prefix"
    :name="$name"
    :blankOption="$blankOption"
    :options="$options"
    :disabled="$disabled"
  />
</div>
