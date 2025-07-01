@stack('head')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#1a1a1a',
                    secondary: '#d4af37',
                    light: '#f8f8f8',
                    dark: '#111111',
                },
                fontFamily: {
                    playfair: ['Playfair Display', 'serif'],
                    roboto: ['Roboto', 'sans-serif'],
                },
                animation: {
                    'fade-in': 'fadeIn 1s ease-in-out',
                    'slide-up': 'slideUp 0.8s ease-out',
                },
                keyframes: {
                    fadeIn: {
                        '0%': {
                            opacity: '0'
                        },
                        '100%': {
                            opacity: '1'
                        },
                    },
                    slideUp: {
                        '0%': {
                            transform: 'translateY(20px)',
                            opacity: '0'
                        },
                        '100%': {
                            transform: 'translateY(0)',
                            opacity: '1'
                        },
                    },
                },
            }
        }
    }
</script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Roboto:wght@300;400;500&display=swap');

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 0;
        background-color: #d4af37;
        transition: width 0.3s;
    }

    .nav-link:hover::after {
        width: 100%;
    }

    .feature-card:hover .feature-number {
        opacity: 0.8;
    }

    .menu-item:hover .menu-img {
        transform: scale(1.05);
    }

    .parallax {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
