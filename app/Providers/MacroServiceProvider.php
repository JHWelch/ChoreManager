<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        EloquentCollection::macro('toOptionsArray', function () {
            /** @var Collection $this */
            return $this->map(fn ($model) => ['value' => $model->id, 'label' => $model->name])->toArray();
        });
    }
}
