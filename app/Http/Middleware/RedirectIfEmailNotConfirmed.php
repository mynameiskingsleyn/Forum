<?php

namespace Forum\Http\Middleware;

use Closure;

class RedirectIfEmailNotConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //dd('on middleware yall');
        if (! $request->user()->confirmed) {
            return redirect('/threads')->with('flash', 'You must first confirm your email address.');
        }
        return $next($request);
    }
}
