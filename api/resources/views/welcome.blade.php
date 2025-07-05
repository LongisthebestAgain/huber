<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-blue-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md text-center">
        @php use Illuminate\Support\Facades\Auth; @endphp
        @if(Auth::check())
            <p class="text-xl mb-4">Welcome, {{ Auth::user()->name }}!</p>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Logout</button>
            </form>
        @else
            <a href="/login" class="text-blue-500 hover:underline">Login</a>
        @endif
    </div>
</body>
</html> 