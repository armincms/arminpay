<?php
namespace Armincms\Arminpay;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider;  
use Laravel\Nova\Nova as LaravelNova; 

class ArminpayServiceProvider extends AuthServiceProvider
{     
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    {    
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');  
        LaravelNova::serving([$this, 'servingNova']);
        $this->registerWebComponents();
        $this->registerPolicies();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {  
        $this->app->singleton('arminpay', function($app) {
            return new GatewayManager($app); 
        });

        $this->app->afterResolving('arminpay', function($manager) { 
            $manager->mergeConfigurations(Models\ArminpayGateway::get()->pluck('config', 'driver')->all());

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
        LaravelNova::resources([
            Nova\Gateway::class,
            Nova\Transaction::class,
        ]);
    } 

    /**
     * Register any HttpSite services.
     * 
     * @return void
     */
    public function registerWebComponents()
    {
        app('site')->push('arminpay', function($arminpay) {
            $arminpay->directory('arminpay');
            // $arminpay->pushComponent(new Components\Transaction);
            $arminpay->pushComponent(new Components\Verify);
        });
    }
}
