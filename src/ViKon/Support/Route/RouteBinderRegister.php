<?php

namespace ViKon\Support\Route;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RouteBinderRegister
 *
 * @package ViKon\Support\Route
 *
 * @author  Kovács Vince<vincekovacs@hotmail.com>
 */
class RouteBinderRegister
{
    /** @type \Illuminate\Contracts\Container\Container */
    protected $container;

    /** @type \Illuminate\Routing\Router */
    protected $router;

    protected $binders = [];

    /**
     * MiddlewareParameterBinder constructor.
     *
     * @param \Illuminate\Contracts\Container\Container $container
     * @param \Illuminate\Routing\Router                $router
     */
    public function __construct(Container $container, Router $router)
    {
        $this->container = $container;
        $this->router    = $router;
    }

    /**
     * Register a model binder for a wildcard.
     *
     * @param  string        $key
     * @param  string        $class
     * @param  \Closure|null $callback
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function model($key, $class, Closure $callback = null)
    {
        $this->bind($key, function ($value) use ($class, $callback) {
            if (is_null($value)) {
                return;
            }

            // For model binders, we will attempt to retrieve the models using the first
            // method on the model instance. If we cannot retrieve the models we'll
            // throw a not found exception otherwise we will return the instance.
            $instance = $this->container->make($class);

            if ($model = $instance->where($instance->getRouteKeyName(), $value)->first()) {
                return $model;
            }

            // If a callback was supplied to the method we will call that to determine
            // what we should do when the model is not found. This just gives these
            // developer a little greater flexibility to decide what will happen.
            if ($callback instanceof Closure) {
                return call_user_func($callback, $value);
            }

            throw new NotFoundHttpException;
        });
    }

    /**
     * Add new route parameter binder
     *
     * @param string          $key
     * @param string|callable $binder
     */
    public function bind($key, $binder)
    {
        if (is_string($binder)) {
            $binder = $this->router->createClassBinding($binder);
        }

        $this->binders[str_replace('-', '_', $key)] = $binder;
    }

    /**
     * Substitute the route bindings onto the route.
     *
     * @param \Illuminate\Routing\Route $route
     *
     * @return \Illuminate\Routing\Route
     */
    public function substituteBindings(Route $route)
    {
        foreach ($route->parameters() as $key => $value) {
            if (isset($this->binders[$key])) {
                $route->setParameter($key, $this->performBinding($key, $value, $route));
            }
        }

        $this->substituteImplicitBindings($route);

        return $route;
    }

    /**
     * Substitute the implicit Eloquent model bindings for the route.
     *
     * @param  \Illuminate\Routing\Route $route
     *
     * @return void
     */
    protected function substituteImplicitBindings(Route $route)
    {
        $parameters = $route->parameters();

        foreach ($route->signatureParameters(Model::class) as $parameter) {
            $class = $parameter->getClass();

            if (array_key_exists($parameter->name, $parameters) &&
                !$route->getParameter($parameter->name) instanceof Model
            ) {
                $method = $parameter->isDefaultValueAvailable() ? 'first' : 'firstOrFail';

                $model = $class->newInstance();

                $route->setParameter(
                    $parameter->name, $model->where(
                    $model->getRouteKeyName(), $parameters[$parameter->name]
                )->{$method}()
                );
            }
        }
    }

    /**
     * Call the binding callback for the given key.
     *
     * @param  string                    $key
     * @param  string                    $value
     * @param  \Illuminate\Routing\Route $route
     *
     * @return mixed
     */
    protected function performBinding($key, $value, $route)
    {
        return call_user_func($this->binders[$key], $value, $route);
    }
}