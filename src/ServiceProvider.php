<?php

namespace Armincms\Arminpay;

use Armincms\Arminpay\Nova\Gateway;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Nova\Nova;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('arminpay', fn ($app) => new GatewayManager($app));
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Nova::resources(config('arminpay.resources', [
            \Armincms\Arminpay\Nova\Gateway::class,
            \Armincms\Arminpay\Nova\Transaction::class,
        ]));

        $this->app->afterResolving('arminpay', function($manager) {
            $resource = config('arminpay.resources.'. Gateway::class, Gateway::class);

            $manager->mergeConfigurations($resource::newModel()->get()->pluck('config', 'driver')->toArray());

            Events\ResolvingArminpay::dispatch($manager);
        });

        app('router')->middleware('web')->prefix('_arminpay')->group(function($router) {
            $router->any('verify/{token}', Http\Controllers\VerifyController::class)->name('arminpay.verify');
        });
    }
}
