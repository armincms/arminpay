<?php
namespace Armincms\Arminpay;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider; 
use Laravel\Nova\Nova; 

class ServiceProvider extends AuthServiceProvider
{     
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [ 
    ]; 

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {   
        $this->mergeConfigFrom(__DIR__.'/config.php', 'arminpay');
        $this->registerPolicies(); 
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');  
        Nova::resources(config('arminpay.resources'));

        $this->app->singleton('arminpay', function($app) {
            return new GatewayManager($app); 
        });

        $this->app->afterResolving('arminpay', function($manager) {  
            $resource = config('arminpay.resources.'. \Armincms\Arminpay\Nova\Gateway::class);

            $manager->mergeConfigurations(with($resource::newModel(), function($gateway) {
                return $gateway->get()->pluck('config', 'driver')->all();
            }));

            Events\ResolvingArminpay::dispatch($manager); 
        });  

        app('router')->middleware('web')->prefix('_arminpay')->group(function($router) {
            $router->any('verify/{token}', Http\Controllers\VerifyController::class)->name('arminpay.verify');
        });
    }  
}
