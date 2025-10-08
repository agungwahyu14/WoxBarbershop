<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    /**
     * Available languages configuration
     */
    protected $availableLanguages = [
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

    /**
     * Switch language and save to session
     *
     * @param Request $request
     * @param string $language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLanguage(Request $request, $language)
    {
        // Validate language code
        if (!array_key_exists($language, $this->availableLanguages)) {
            return redirect()->back()->with('error', 'Bahasa tidak didukung / Language not supported');
        }

        // Set application locale
        App::setLocale($language);
        
        // Store language preference in session
        Session::put('locale', $language);
        
        // Store in user preferences if authenticated
        if (auth()->check()) {
            try {
                $user = auth()->user();
                $user->update(['language_preference' => $language]);
            } catch (\Exception $e) {
                // If column doesn't exist, we'll just use session
                \Log::info('Language preference not saved to database: ' . $e->getMessage());
            }
        }

        // Success message based on selected language
        $successMessage = $language === 'id' 
            ? 'Bahasa berhasil diubah ke Indonesia' 
            : 'Language successfully changed to English';

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Get current language information
     *
     * @return array
     */
    public function getCurrentLanguage()
    {
        $currentLocale = App::getLocale();
        return $this->availableLanguages[$currentLocale] ?? $this->availableLanguages['id'];
    }

    /**
     * Get all available languages
     *
     * @return array
     */
    public function getAvailableLanguages()
    {
        return $this->availableLanguages;
    }

    /**
     * API endpoint to get language data (for AJAX requests)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLanguageData()
    {
        return response()->json([
            'current' => $this->getCurrentLanguage(),
            'available' => $this->availableLanguages,
            'locale' => App::getLocale()
        ]);
    }

    /**
     * AJAX language switch
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchLanguageAjax(Request $request)
    {
        $language = $request->input('language');

        // Validate language code
        if (!array_key_exists($language, $this->availableLanguages)) {
            return response()->json([
                'success' => false,
                'message' => 'Bahasa tidak didukung / Language not supported'
            ], 400);
        }

        // Set application locale
        App::setLocale($language);
        
        // Store language preference in session
        Session::put('locale', $language);
        
        // Store in user preferences if authenticated
        if (auth()->check()) {
            try {
                $user = auth()->user();
                $user->update(['language_preference' => $language]);
            } catch (\Exception $e) {
                \Log::info('Language preference not saved to database: ' . $e->getMessage());
            }
        }

        $successMessage = $language === 'id' 
            ? 'Bahasa berhasil diubah ke Indonesia' 
            : 'Language successfully changed to English';

        return response()->json([
            'success' => true,
            'message' => $successMessage,
            'current_language' => $this->availableLanguages[$language],
            'reload_required' => true
        ]);
    }
}