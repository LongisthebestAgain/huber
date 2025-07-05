<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Hubber</title>
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
            <div class="flex items-center mt-4 space-x-2">
                <span class="w-4 h-4 bg-blue-400 rounded-full flex items-center justify-center text-white text-xs">&#10003;</span>
                <span class="w-4 h-4 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs">2</span>
                <span class="w-4 h-4 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-xs">3</span>
            </div>
        </div>
        <form id="registerForm">
            <div class="flex space-x-2 mb-4">
                <input type="text" id="first_name" name="first_name" placeholder="First Name" class="w-1/2 px-3 py-2 border rounded" required>
                <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="w-1/2 px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <input type="email" id="email" name="email" placeholder="Email Address" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <input type="text" id="phone" name="phone" placeholder="Phone Number" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-4">
                <input type="password" id="password" name="password" placeholder="Password" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="mb-6">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div class="flex justify-between items-center mb-4">
                <button type="button" onclick="window.history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Back</button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Continue</button>
            </div>
            <div id="registerError" class="bg-red-100 text-red-700 p-2 rounded mb-4 mt-2 hidden"></div>
        </form>
        <div class="mt-2 text-center text-sm">
            Already have an account? <a href="/login" class="text-blue-500 hover:underline">Sign in</a>
        </div>
    </div>
    <script>
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const first_name = document.getElementById('first_name').value;
        const last_name = document.getElementById('last_name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const password_confirmation = document.getElementById('password_confirmation').value;
        const errorDiv = document.getElementById('registerError');
        errorDiv.classList.add('hidden');
        errorDiv.textContent = '';
        try {
            const response = await fetch('/api/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ first_name, last_name, email, phone, password, password_confirmation })
            });
            const data = await response.json();
            if (data.success) {
                window.location.href = '/login';
            } else {
                errorDiv.textContent = data.message || 'Registration failed.';
                errorDiv.classList.remove('hidden');
            }
        } catch (err) {
            errorDiv.textContent = 'An error occurred. Please try again.';
            errorDiv.classList.remove('hidden');
        }
    });
    </script>
    <style>
    .role-btn.selected {
        background: #2563eb;
        color: #fff;
        border-color: #2563eb;
    }
    </style>
</body>
</html> 