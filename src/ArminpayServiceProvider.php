<?php
namespace Component\Arminpay;

use Illuminate\Support\ServiceProvider;      

class ArminpayServiceProvider extends ServiceProvider
{     
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Define your route model bindings, pattern filters, etc.
     * 
     * @return void
     */
    public function boot()
    { 
        $this->routes(); 
        $this->loadJsonTranslationsFrom(__DIR__.'/resources/lang'); 
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSites(); 
        $this->registerResources(); 

        $this->app->singleton('arminpay.gateway', function($app) {
            return new GatewayManager($app); 
        });

        $this->app->bind('arminpay.order', function($app) {
            return new OrderableManager($app);
        }); 

        $this->app->bind('arminpay.trace', function($app) {
            return $app['session']->get('arminpay.trace');
        });  

        $this->app->afterResolving('arminpay.gateway', function($manager) { 
            Gateway::get()->each(function($gateway) use ($manager) { 
                if($manager->has($gateway->name)) {   
                    $logo = $gateway->logo['original'] ?? null;
                    $label = $gateway->title;

                    $active = (boolean) $gateway->isActive();

                    $manager->resolve($gateway->name)->withConfig( 
                        $gateway->config->merge(compact('logo', 'active', 'label'))->toArray()
                    ); 
                }
            });
        });
    }

    public function registerResources()
    {
        $this->app->extend('armin.resource', function($resources) { 
            $menu = \Menu::get('bigMenu')->add(
                'arminpay::title.arminpay', ['nickname' => 'arminpay']
            );

            $resources->register(
                'gateway', Http\Controllers\GatewayController::class, ['except' => ['create']]
            );
            $resources->register(
                'order', Http\Controllers\OrderController::class
            );

            return $resources;
        }); 
    }

    protected function registerSites()
    { 
        $this->app->extend('site', function($manager) {
            $manager->push('arminpay', function ($lcSite) {
                $lcSite
                    ->directory('arminpay') 
                    ->pushComponent(new Components\InvoiceComponent);
            });

            return $manager;
        }); 
    }
    
    public function routes()
    {
        app('router')
            // ->middleware('web')
            ->prefix('arminpay')
            ->as('arminpay.')
            ->namespace(__NAMESPACE__.'\Http\Controllers\Web')
            ->group(function($router) {
                $router->post('cart/store', 'CartController@store')->name('cart.store');
                $router->post('cart/remove', 'CartController@remove')->name('cart.remove'); 

                $router->post('order', 'OrderCreateController')->name(
                    'order.store'
                );
                $router->post('order/pay', 'OrderPaymentController')->name(
                    'order.pay'
                );
                $router->any(
                    'transaction/{transaction}/verify', 'VerifyController'
                )->name('transaction.verify');
                $router->post('account', 'AccountCreateController')->name(
                    'account.store'
                ); 
            }); 
    }
}
