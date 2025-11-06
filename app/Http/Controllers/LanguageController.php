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
        // $successMessage = $language === 'id' 
        //     ? 'Bahasa berhasil diubah ke Indonesia' 
        //     : 'Language successfully changed to English';

        return redirect()->back()->with('success')->with('scroll_to_top', true);
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
        $currentUrl = $request->input('url', url()->current());

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

        $successTitle = $language === 'id' 
            ? 'Berhasil!' 
            : 'Success!';

        // Prepare response with content updates
        $response = [
            'success' => true,
            'message' => $successMessage,
            'success_title' => $successTitle,
            'current_language' => $this->availableLanguages[$language],
            'reload_required' => false
        ];

        // Add content updates for specific pages (no reload)
        try {
            $path = parse_url($currentUrl, PHP_URL_PATH);
            
            // Update content for common pages without reload
            if (in_array($path, ['/', '/dashboard', '/profile']) || 
                str_starts_with($path, '/#')) {
                
                $response['content'] = $this->getPageContent($language, $path);
            } else {
                // For admin pages and complex routes, require reload
                $response['reload_required'] = true;
            }
        } catch (\Exception $e) {
            \Log::info('Content update failed, requiring reload: ' . $e->getMessage());
            $response['reload_required'] = true;
        }

        return response()->json($response);
    }

    /**
     * Get page content for language switch
     *
     * @param string $language
     * @param string $path
     * @return array
     */
    private function getPageContent($language, $path)
    {
        $content = [];

        try {
            // Set locale for content generation
            App::setLocale($language);

            // Get navigation translations
            $content['nav'] = [
                'home' => __('menu.home'),
                'services' => __('menu.services'),
                'products' => __('menu.products'),
                'booking' => __('menu.booking'),
                'about' => __('menu.about'),
                'dashboard' => __('menu.dashboard'),
                'profile' => __('menu.profile'),
                'logout' => __('menu.logout')
            ];

            // Get page title
            if ($path === '/') {
                $content['title'] = __('welcome.title');
            } elseif ($path === '/dashboard') {
                $content['title'] = __('dashboard.title');
            } elseif ($path === '/profile') {
                $content['title'] = __('profile.title');
            }

            // For simple pages, we can update main content
            if ($path === '/') {
                // Only update static text content, not dynamic data
                $content['main'] = null; // Keep existing content, just update translations via JS
            }

        } catch (\Exception $e) {
            \Log::error('Error generating page content: ' . $e->getMessage());
        }

        return $content;
    }
}
