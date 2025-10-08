<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Available languages
        $availableLanguages = ['id', 'en'];
        $defaultLanguage = 'id';

        // Get language from various sources in priority order
        $language = $this->getLanguagePreference($request, $availableLanguages, $defaultLanguage);

        // Set application locale
        App::setLocale($language);
        
        // Store in session if not already set
        if (!Session::has('locale') || Session::get('locale') !== $language) {
            Session::put('locale', $language);
        }

        return $next($request);
    }

    /**
     * Get user's language preference from various sources
     *
     * @param Request $request
     * @param array $availableLanguages
     * @param string $defaultLanguage
     * @return string
     */
    private function getLanguagePreference(Request $request, array $availableLanguages, string $defaultLanguage): string
    {
        // 1. Check if language is being switched via URL parameter
        if ($request->has('lang') && in_array($request->get('lang'), $availableLanguages)) {
            return $request->get('lang');
        }

        // 2. Check authenticated user's language preference (if column exists)
        if (auth()->check()) {
            try {
                $userLanguage = auth()->user()->language_preference ?? null;
                if ($userLanguage && in_array($userLanguage, $availableLanguages)) {
                    return $userLanguage;
                }
            } catch (\Exception $e) {
                // Column might not exist yet
            }
        }

        // 3. Check session
        $sessionLanguage = Session::get('locale');
        if ($sessionLanguage && in_array($sessionLanguage, $availableLanguages)) {
            return $sessionLanguage;
        }

        // 4. Check browser's Accept-Language header
        $browserLanguage = $this->getBrowserLanguage($request, $availableLanguages);
        if ($browserLanguage) {
            return $browserLanguage;
        }

        // 5. Return default language
        return $defaultLanguage;
    }

    /**
     * Get preferred language from browser's Accept-Language header
     *
     * @param Request $request
     * @param array $availableLanguages
     * @return string|null
     */
    private function getBrowserLanguage(Request $request, array $availableLanguages): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header
        $languages = [];
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)(?:;q=([0-9.]+))?/i', $acceptLanguage, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $language) {
                $quality = isset($matches[2][$index]) ? (float)$matches[2][$index] : 1.0;
                $languages[strtolower($language)] = $quality;
            }
            
            // Sort by quality (preference)
            arsort($languages);
            
            // Check for exact matches first
            foreach ($languages as $lang => $quality) {
                if (in_array($lang, $availableLanguages)) {
                    return $lang;
                }
            }
            
            // Check for language family matches (e.g., 'en-US' -> 'en')
            foreach ($languages as $lang => $quality) {
                $langCode = substr($lang, 0, 2);
                if (in_array($langCode, $availableLanguages)) {
                    return $langCode;
                }
            }
        }

        return null;
    }
}