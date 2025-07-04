<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-blue-400 to-blue-700 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <div class="flex flex-col items-center mb-6">
            <div class="bg-blue-100 p-3 rounded-full mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <h2 class="text-2xl font-bold">Login to Hubber</h2>
            <p class="text-gray-500 text-sm mt-1">Sign in to your account</p>
        </div>
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email Address</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded" required autofocus value="{{ old('email') }}">
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</button>
        </form>
        <div class="mt-4 text-center text-sm">
            Don't have an account?
            <a href="/register" class="inline-block mt-2 bg-blue-100 text-blue-700 px-6 py-2 rounded hover:bg-blue-200 font-semibold">Register</a>
        </div>
        <div class="mt-2 text-center">
            <a href="/" class="text-blue-500 hover:underline">Back to Home</a>
        </div>
    </div>
</body>
</html> 