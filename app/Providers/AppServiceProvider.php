<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('markdown', function ($markdown) {
            if ($markdown) {
                return '
                <?php
                $converter = new \League\CommonMark\CommonMarkConverter([\'html_input\' => \'escape\', \'allow_unsafe_links\' => false]);
                echo $converter->convertToHtml((string) ' . $markdown . ');
                ?>
                ';
            }

            return '<?php ob_start(); ?>';
        });
    }
}
