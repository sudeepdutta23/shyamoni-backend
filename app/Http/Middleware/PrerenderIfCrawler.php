<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Api\HomeController;


use Illuminate\Http\Request;

class PrerenderIfCrawler
{

    // public function handle(Request $request, Closure $next)
    // {
    //     return $next($request);
    // }

    public function handle(Request $request, Closure $next, string $routeName = null)
    {
    //     if ($this->shouldPrerender($request)) {
    //         define('SHOULD_PRERENDER', true);

    //     // Always fallback to client routes if not prerendering
    //     // otherwise prerender routes will override client side routing
    //     }
        if ($routeName !== 'homepage') {
            return app(HomeController::class)->show();
        }

        return $next($request);
    }



}
