<?php

namespace App\Http\Middleware;

use App\Services\ClareStore;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ShareClareStore
{
    public function __construct(private ClareStore $store)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        App::setLocale($this->store->locale());
        $this->store->share();

        return $next($request);
    }
}
