<?php

if (!function_exists('current_language')) {
    /**
     * Get current language information
     * 
     * @return array
     */
    function current_language(): array
    {
        $locale = app()->getLocale();
        $languages = [
            'id' => [
                'code' => 'id',
                'name' => 'Indonesia',
                'flag' => '🇮🇩'
            ],
            'en' => [
                'code' => 'en',
                'name' => 'English', 
                'flag' => '🇺🇸'
            ]
        ];

        return $languages[$locale] ?? $languages['id'];
    }
}

if (!function_exists('available_languages')) {
    /**
     * Get all available languages
     * 
     * @return array
     */
    function available_languages(): array
    {
        return [
            'id' => [
                'code' => 'id',
                'name' => __('general.indonesian'),
                'flag' => '🇮🇩'
            ],
            'en' => [
                'code' => 'en',
                'name' => __('general.english'),
                'flag' => '🇺🇸'
            ]
        ];
    }
}

if (!function_exists('language_route')) {
    /**
     * Generate language switch route
     * 
     * @param string $language
     * @return string
     */
    function language_route(string $language): string
    {
        return route('language.switch', $language);
    }
}

if (!function_exists('translate_or_fallback')) {
    /**
     * Translate with fallback to key if translation doesn't exist
     * 
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function translate_or_fallback(string $key, array $replace = [], string $locale = ""): string
    {
        $translation = trans($key, $replace, $locale);
        
        // If translation is the same as key, it means translation doesn't exist
        if ($translation === $key) {
            // Return a more readable format
            return ucwords(str_replace(['_', '.'], ' ', last(explode('.', $key))));
        }
        
        return $translation;
    }
}