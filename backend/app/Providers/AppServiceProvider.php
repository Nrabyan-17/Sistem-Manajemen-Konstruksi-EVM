<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        \Illuminate\Support\Facades\Blade::directive('rupiah', function ($expression) {
            return "<?php echo \\App\\Support\\CurrencyHelper::format({$expression}); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('rupiahFull', function ($expression) {
            return "<?php echo \\App\\Support\\CurrencyHelper::formatFull({$expression}); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('rupiahDiff', function ($expression) {
            return "<?php echo \\App\\Support\\CurrencyHelper::formatDiff({$expression}); ?>";
        });
    }
}
