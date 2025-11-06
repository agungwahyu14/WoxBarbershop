<footer id="contact" class="bg-dark text-white pt-20 pb-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12">
            <div>
                <h3 class="text-2xl font-playfair font-bold mb-6 text-secondary">WOX'S Barbershop.</h3>
                <p class="text-gray-400 mb-6">
                    {{ __('welcome.footer_description') }}
                </p>
                {{-- <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-secondary transition-colors duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-secondary transition-colors duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-secondary transition-colors duration-300">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div> --}}
            </div>

            <div>
                <h4 class="text-lg font-bold mb-6">{{ __('welcome.operating_hours') }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex justify-between">
                        <span>{{ __('welcome.monday_sunday') }} : 11.00 - 20.00</span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-6">{{ __('welcome.contact_us') }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex items-start">
                        <i class="fas fa-map-marker-alt mt-1 mr-3 text-secondary"></i>
                        <span>Jl. Barber No. 123, Jakarta Selatan</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-phone-alt mr-3 text-secondary"></i>
                        <span>(021) 1234-5678</span>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-secondary"></i>
                        <span>info@woxsbarbershop.com</span>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-bold mb-6">{{ __('welcome.quick_links') }}</h4>
                <ul class="space-y-3 text-gray-400">
                    <li>
                        <a href="#beranda"
                            class="hover:text-secondary transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-secondary"></i> {{ __('welcome.home') }}
                        </a>
                    </li>
                    <li>
                        <a href="#layanan"
                            class="hover:text-secondary transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-secondary"></i>
                            {{ __('welcome.services') }}
                        </a>
                    </li>
                    <li>
                        <a href="#tentang"
                            class="hover:text-secondary transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-secondary"></i>
                            {{ __('welcome.about_us') }}
                        </a>
                    </li>
                    <li>
                        <a href="#produk" class="hover:text-secondary transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-secondary"></i>
                            {{ __('welcome.products') }}
                        </a>
                    </li>
                    <li>
                        <a href="#reservasi"
                            class="hover:text-secondary transition-colors duration-300 flex items-center">
                            <i class="fas fa-chevron-right text-xs mr-2 text-secondary"></i>
                            {{ __('welcome.reservation') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6 text-center text-gray-500">
            <p>&copy; {{ now()->year }} WOX'S Barbershop. {{ __('welcome.footer_copyright') }}</p>
        </div>
    </div>
</footer>
