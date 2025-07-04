<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Hubber - Choose Role</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-400 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <div class="bg-blue-100 p-3 rounded-full mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <h2 class="text-2xl font-bold">Join Hubber</h2>
            <p class="text-gray-500 text-sm mt-1">Create your account and start your journey</p>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 mb-2 text-center">Choose Your Role</label>
            <div class="flex space-x-6 justify-center">
                <button onclick="window.location.href='/register/user'" class="role-btn selected flex flex-col items-center border-2 border-gray-400 rounded-2xl px-8 py-6 focus:outline-none shadow-md hover:scale-105 transition-transform bg-gradient-to-br from-gray-100 to-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="#6b7280"/>
                        <rect x="6" y="14" width="12" height="6" rx="3" stroke="currentColor" stroke-width="2" fill="#9ca3af"/>
                    </svg>
                    <span class="font-bold text-lg">User</span>
                    <span class="text-xs text-gray-500 mt-1">Book rides and travel</span>
                </button>
                <button onclick="window.location.href='/register/driver'" class="role-btn flex flex-col items-center border-2 border-gray-300 rounded-2xl px-8 py-6 focus:outline-none shadow-md hover:scale-105 transition-transform bg-gradient-to-br from-gray-100 to-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <rect x="3" y="11" width="18" height="7" rx="3.5" stroke="#3b82f6" stroke-width="2" fill="#60a5fa"/>
                        <circle cx="7.5" cy="18" r="2" fill="#3b82f6"/>
                        <circle cx="16.5" cy="18" r="2" fill="#3b82f6"/>
                        <rect x="7" y="7" width="10" height="4" rx="2" fill="#93c5fd"/>
                    </svg>
                    <span class="font-bold text-lg">Driver</span>
                    <span class="text-xs text-gray-500 mt-1">Offer rides and earn</span>
                </button>
            </div>
        </div>
        <div class="mt-2 text-center text-sm">
            Already have an account? <a href="/login" class="text-blue-500 hover:underline">Sign in</a>
        </div>
    </div>
    <style>
    .role-btn.selected {
        color: #2563eb !important;
    }
    .role-btn {
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .role-btn:hover {
        box-shadow: 0 8px 32px 0 rgba(107,114,128,0.18);
        transform: scale(1.05);
    }
    </style>
</body>
</html> 