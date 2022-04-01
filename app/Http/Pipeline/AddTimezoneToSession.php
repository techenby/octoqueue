<?php

namespace App\Http\Pipeline;

use Illuminate\Http\Request;

class AddTimezoneToSession
{
    public function __invoke(Request $request)
    {
        $timezone = $request->get('timezone', 'America/Detroit');

        session(['timezone' => $timezone]);

        return next($request);
    }
}
