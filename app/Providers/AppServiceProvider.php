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
                return "<?php echo \Illuminate\Mail\Markdown::Parse((string) {$markdown}); ?>";
            }

            return '<?php ob_start(); ?>';
        });
    }
}
