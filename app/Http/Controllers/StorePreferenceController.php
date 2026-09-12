<?php

namespace App\Http\Controllers;

use App\Services\ClareStore;
use Illuminate\Http\Request;

class StorePreferenceController extends Controller
{
    public function locale(Request $request, string $locale)
    {
        $locale = strtolower($locale);
        if (! isset(ClareStore::LOCALES[$locale])) {
            $locale = 'en';
        }
        session(['clare_locale' => $locale]);

        return redirect($this->back($request));
    }

    public function currency(Request $request, string $currency)
    {
        $currency = strtoupper($currency);
        if (! isset(ClareStore::CURRENCIES[$currency])) {
            $currency = 'USD';
        }
        session(['clare_currency' => $currency]);

        return redirect($this->back($request));
    }

    private function back(Request $request): string
    {
        $url = (string) $request->headers->get('referer', '/');
        if ($url === '' || ! str_starts_with($url, $request->getSchemeAndHttpHost())) {
            return '/';
        }

        return $url;
    }
}
