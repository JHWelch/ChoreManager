<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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

        /**
         * Convert a snake_cased string to Title Case, e.g. field_name => Field Name.
         * @param string $string
         * @return string
         */
        Str::macro('snakeToTitle', fn ($string) => Str::of($string)->snake()->replace('_', ' ')->title()->__toString());

        /**
         * Convert a snake_cased string to Sentence case, e.g. field_name => Field name.
         *
         * @param string $string
         * @return string
         */
        Str::macro('snakeToLabel', fn ($string) => ucfirst(str_replace('_', ' ', $string)));

        Carbon::macro('diffDaysForHumans', function () {
            /** @var Carbon $this */
            return $this == today()
                ? 'today'
                : $this->diffForHumans(
                    today(),
                    [
                    'options' => \Carbon\CarbonInterface::ONE_DAY_WORDS,
                    'syntax'  => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                    ]
                );
        });
    }
}
