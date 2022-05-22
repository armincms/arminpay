<?php
namespace Armincms\Arminpay;

use Illuminate\Contracts\Support\DeferrableProvider;  
use Illuminate\Foundation\Support\Providers\AuthServiceProvider; 
use Laravel\Nova\Nova; 

class ServiceProvider extends AuthServiceProvider implements DeferrableProvider
{     
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Models\Course::class => Policies\Course::class, 
    ]; 

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {   
        $this->mergeConfigFrom(__DIR__.'/config.php', 'arminpay');
        $this->registerPolicies();
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
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
    } 

    /**
     * Register any Nova services.
     *
     * @return void
     */
    public function servingNova()
    {
        
    }  

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['arminpay'];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Illuminate\Console\Events\ArtisanStarting::class,
            \Laravel\Nova\Events\ServingNova::class,
        ];
    }
}
