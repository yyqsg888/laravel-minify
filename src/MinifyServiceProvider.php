<?php

namespace Fahlisaputra\Minify;

use Fahlisaputra\Minify\Controllers\HttpConnectionHandler;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Facades\Route as RouteFacade;

class MinifyServiceProvider extends BaseServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->registerPublishables();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
    }

    protected function registerPublishables() {
        $this->publishes([
            __DIR__.'/../config/minify.php' => config_path('minify.php'),
        ], 'config');
    }

    public function registerConfig() {
        $this->mergeConfigFrom(__DIR__ . "/../config/minify.php", "minify.php");
    }

    public function registerRoutes() {
        RouteFacade::get('/_minify/{file}', HttpConnectionHandler::class)
            ->name('minify.assets');
    }
}
