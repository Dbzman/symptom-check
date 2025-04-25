<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            // Get browser language
            $browserLang = $request->getPreferredLanguage();

            // Get the language code (first 2 characters, e.g., 'en' from 'en-US')
            $langCode = substr($browserLang, 0, 2);

            // Check if the language is supported (you can expand this array with supported languages)
            $supportedLanguages = ['en', 'de'];

            if (in_array($langCode, $supportedLanguages)) {
                App::setLocale($langCode);
                Session::put('locale', $langCode);
            }
        }

        return $next($request);
    }
}
