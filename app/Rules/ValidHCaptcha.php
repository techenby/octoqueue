<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidHCaptcha implements Rule
{
    public function passes($attribute, $value)
    {
        $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
            'secret' => config('services.hcaptcha.secret'),
            'response' => $value,
        ]);

        return $response->json('success');
    }

    public function message()
    {
        return 'Invalid CAPTCHA. You need to prove you are human.';
    }
}
