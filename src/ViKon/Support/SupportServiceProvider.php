<?php

namespace ViKon\Support;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;
use ViKon\Support\Route\RouteBinderRegister;

/**
 * Class SupportServiceProvider
 *
 * @package ViKon\Support
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class SupportServiceProvider extends ServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function register()
    {
        $this->registerParameterBinder();
    }

    /**
     * Boot support service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->loadResources();
    }

    /**
     * Load resources for support package
     *
     * @return void
     */
    protected function loadResources()
    {
        $resourcePath = __DIR__ . '/../../resources/';

        $this->loadViewsFrom($resourcePath . 'views', 'vi-kon.support');
    }

    /**
     * Register route parameter binder
     *
     * @return void
     */
    protected function registerParameterBinder()
    {
        $this->app->singleton(RouteBinderRegister::class, function (Container $container) {
            return new RouteBinderRegister($container, $container->make('router'));
        });
    }
}