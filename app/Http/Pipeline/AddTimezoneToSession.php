<?php

namespace App\Http\Pipeline;

class AddTimezoneToSession
{
    public function __invoke($request, $next)
    {
        $timezone = $request->get('timezone', 'America/Detroit');

        session(['timezone' => $timezone]);

        return $next($request);
    }
}
