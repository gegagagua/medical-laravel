<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dashboard - {{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center justify-between mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
                    <button 
                        onclick="logout()" 
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                    >
                        Logout
                    </button>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-blue-900 mb-4">Welcome to Your Dashboard!</h2>
                    <p class="text-blue-700">You have successfully logged in.</p>
                    <p class="text-blue-700 mt-2">This is a protected route that requires authentication.</p>
                </div>
            </div>
        </div>

        <script>
        async function logout() {
            try {
                const token = localStorage.getItem('auth_token');
                await axios.post('/api/auth/logout', {}, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                localStorage.removeItem('auth_token');
                window.location.href = '/auth';
            } catch (error) {
                console.error('Logout error:', error);
                localStorage.removeItem('auth_token');
                window.location.href = '/auth';
            }
        }
        </script>
    </body>
</html>

