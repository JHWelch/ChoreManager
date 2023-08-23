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
     */
    public function boot(): void
    {
        /**
         * Convert a collection to an array of options that can be used in an HTML select.
         *
         * @return array<array<string, mixed>>
         */
        EloquentCollection::macro('toOptionsArray', function () {
            /** @var Collection $this */
            return $this->map(fn ($model) => ['value' => $model->id, 'label' => $model->name])->toArray();
        });

        /**
         * The next item in a collection after a given value.
         *
         * @param  mixed  $needle - The item to search for
         * @param  bool  $strict - Whether to use strict comparison in the collection search
         * @param  bool  $wrap - if $needle specifies last item in collection, return the first.
         * @return mixed
         */
        EloquentCollection::macro('nextAfter', function ($needle, $strict = false, $wrap = false) {
            /** @var Collection $this */
            $found_index = $this->values()->search($needle, $strict);

            return $wrap && $found_index === $this->count() - 1
                ? $this->first()
                : $this->values()->get($found_index + 1);
        });

        /**
         * Convert a snake_cased string to Title Case, e.g. field_name => Field Name.
         *
         * @param  string  $string
         * @return string
         */
        Str::macro('snakeToTitle', fn ($string) => Str::of($string)->snake()->replace('_', ' ')->title()->__toString());

        /**
         * Convert a snake_cased string to Sentence case, e.g. field_name => Field name.
         *
         * @param  string  $string
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
                        'syntax' => \Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW,
                    ]
                );
        });
    }
}
