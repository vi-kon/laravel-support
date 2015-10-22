<?php

namespace ViKon\Support\Middleware\View;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Support\MessageBag;

class ShareSuccessesFromSession
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new success binder instance.
     *
     * @param  \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
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
        // If the current session has an "successes" variable bound to it, we will share
        // its value with all view instances so the views can easily access successes
        // without having to bind. An empty bag is set when there aren't successes.
        $this->view->share(
            'successes', $request->session()->get('successes', new MessageBag())
        );

        // Putting the successes in the view for every view allows the developer to just
        // assume that some successes are always available, which is convenient since
        // they don't have to continually run checks for the presence of successes.

        return $next($request);
    }
}
