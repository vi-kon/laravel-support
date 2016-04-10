<?php

namespace ViKon\Support;

use Illuminate\Support\ServiceProvider;

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