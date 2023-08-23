<?php

namespace App\Http\Livewire\Concerns;

trait TrimAndNullEmptyStrings
{
    public function updatedTrimAndNullEmptyStrings(string $name, mixed $value): void
    {
        if (is_string($value)) {
            $value = trim($value);
            $value = $value === '' ? null : $value;

            data_set($this, $name, $value);
        }
    }
}
