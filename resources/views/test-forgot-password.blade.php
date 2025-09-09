<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Forgot Password - WOX Barbershop</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto py-12">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
            <h2 class="text-2xl font-bold text-center mb-6">Test Forgot Password</h2>
            
            <div class="space-y-4">
                <p class="text-gray-600">Klik tombol di bawah untuk mengakses halaman forgot password:</p>
                
                <a href="{{ route('password.request') }}" 
                   class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition-colors">
                    <i class="fas fa-key mr-2"></i>
                    Forgot Password
                </a>
                
                <div class="border-t pt-4">
                    <h3 class="font-semibold mb-2">Test dengan Email:</h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• admin@woxbarbershop.com</li>
                        <li>• ahmad@woxbarbershop.com</li>
                        <li>• john@example.com</li>
                    </ul>
                </div>
                
                <div class="border-t pt-4">
                    <h3 class="font-semibold mb-2">Langkah Testing:</h3>
                    <ol class="text-sm text-gray-600 space-y-1 list-decimal list-inside">
                        <li>Klik tombol "Forgot Password"</li>
                        <li>Masukkan salah satu email di atas</li>
                        <li>Klik "Kirim Link Reset Password"</li>
                        <li>Cek log email atau Mailtrap</li>
                        <li>Klik link di email untuk reset password</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
