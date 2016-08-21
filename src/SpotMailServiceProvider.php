<?php

// define namspace of this class
namespace Lakshmajim\SpotMail;

// import required namespaces here
use Illuminate\Support\ServiceProvider;
use Lakshmajim\SpotMail\Middleware\SpotMailMiddleware;

/**
 * ApotMailServiceProvider 
 *
 * SpotMail Service Provider to support integration with 
 * Laravel Framework, which binds the controllers with t
 * -he Laravel applications
 *
 * @package SpotMail
 * @version 1.0.0
 * @since   Class available since Release 1.0.0
 * @author  lakshmajim < lakshmajee88@gmail.com >
 */
class SpotMailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        // include __DIR__ . '/test.php';
        $this->app['router']->middleware('email.validate', SpotMailMiddleware::class);

        $this->publishes([
            __DIR__.'/config' => config_path()
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

        // $this->mergeConfigFrom(__DIR__.'/config/spotmail.php', 'spotmail');

        $this->app['spotmail'] = $this->app->share(function($app) {
            return new SpotMail;
        });
    }
}
// end of class SpotMailServiceProvider
// end of file SpotMailServiceProvider.php