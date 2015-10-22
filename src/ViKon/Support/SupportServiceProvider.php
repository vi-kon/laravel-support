<?php

namespace ViKon\Support;

use Illuminate\Support\ServiceProvider;
use ViKon\Support\Middleware\View\ShareSuccessesFromSession;

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
    }

    /**
     * Boot support service provider
     *
     * @return void
     */
    public function boot()
    {
        $this->loadResources();

        $this->app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware(ShareSuccessesFromSession::class);
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
}