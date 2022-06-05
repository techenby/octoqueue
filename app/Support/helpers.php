<?php

use Illuminate\Support\Str;

if (! function_exists('isUrlAccessible')) {
    function isUrlAccessible($url)
    {
        //check, if a valid url is provided
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
        }

        //initialize curl
        $curlInit = curl_init($url);
        curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curlInit, CURLOPT_HEADER, true);
        curl_setopt($curlInit, CURLOPT_NOBODY, true);
        curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);

        //get answer
        $response = curl_exec($curlInit);

        curl_close($curlInit);

        return $response ? true : false;
    }
}

if (! function_exists('isInternetExplorer')) {
    function isInternetExplorer()
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT']) ||
            (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false) ||
            (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; Touch; rv:11.0') !== false);
        }
    }
}

if (! function_exists('stripeUrl')) {
    function stripeUrl($text)
    {
        if (config('app.env') === 'production') {
            return 'https://dashboard.stripe.com/search?query=' . $text;
        }

        return 'https://dashboard.stripe.com/test/search?query=' . $text;
    }
}

if (! function_exists('slugify')) {
    function slugify($string)
    {
        return Str::of($string)->slug();
    }
}
