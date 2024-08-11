<?php

namespace App\Providers;

use Blade;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        /**
         * Blade directive that will display any Markdown text as formatted HTML.
         *
         * @param  string  $markdown
         * @return string php of the blade directive
         */
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

        $this->bootRoute();
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });


    }
}
