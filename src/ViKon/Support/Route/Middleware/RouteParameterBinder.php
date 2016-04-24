<?php

namespace ViKon\Support\Route\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use ViKon\Support\Route\RouteBinderRegister;

/**
 * Class RouteParameterBinder
 *
 * @package ViKon\Support\Route\Middleware
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class RouteParameterBinder
{
    /** @type \Illuminate\Contracts\Container\Container */
    protected $container;

    /**
     * RouteParameterBinder constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $route  = $this->container->make(Route::class);
        $binder = $this->container->make(RouteBinderRegister::class);

        $binder->substituteBindings($route);

        return $next($request);
    }
}