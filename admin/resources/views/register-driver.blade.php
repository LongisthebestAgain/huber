<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Hubber - Driver Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <form id="registerDriverForm">
            <h3 class="text-lg font-semibold mb-2">Personal Information</h3>
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
            <div class="mb-6 p-4 border rounded bg-gray-50">
                <div class="flex items-center mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13l2-2m0 0l7-7 7 7M13 5v6h6" /></svg>
                    <span class="font-semibold">Driver Information</span>
                </div>
                <div class="flex space-x-2 mb-2">
                    <input type="text" id="license_number" name="license_number" placeholder="License Number" class="w-1/2 px-3 py-2 border rounded" required>
                    <input type="date" id="license_expiry" name="license_expiry" placeholder="License Expiry" class="w-1/2 px-3 py-2 border rounded" required>
                </div>
                <div class="mb-2">
                    <input type="text" id="vehicle_model" name="vehicle_model" placeholder="Vehicle Model (e.g. Camry)" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="flex space-x-2 mb-2">
                    <input type="number" id="vehicle_year" name="vehicle_year" placeholder="Vehicle Year" class="w-1/2 px-3 py-2 border rounded" required>
                    <input type="text" id="vehicle_color" name="vehicle_color" placeholder="Vehicle Color" class="w-1/2 px-3 py-2 border rounded" required>
                </div>
                <div class="flex space-x-2 mb-2">
                    <input type="text" id="license_plate" name="license_plate" placeholder="License Plate" class="w-1/2 px-3 py-2 border rounded" required>
                    <input type="number" id="vehicle_seats" name="vehicle_seats" placeholder="Number of Seats" class="w-1/2 px-3 py-2 border rounded" required min="1" max="20">
                </div>
            </div>
            
            <!-- Vehicle Photos Section -->
            <div class="mb-6 p-4 border rounded bg-gray-50">
                <div class="flex items-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span class="font-semibold">Vehicle Photos</span>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Front View</label>
                    <input type="file" id="vehicle_photo_1" name="vehicle_photo_1" accept="image/*" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Side View</label>
                    <input type="file" id="vehicle_photo_2" name="vehicle_photo_2" accept="image/*" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rear View</label>
                    <input type="file" id="vehicle_photo_3" name="vehicle_photo_3" accept="image/*" class="w-full px-3 py-2 border rounded" required>
                </div>
            </div>
            
            <div class="flex justify-between items-center mb-4">
                <button type="button" onclick="window.history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Back</button>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Continue</button>
            </div>
            <div id="registerDriverError" class="bg-red-100 text-red-700 p-2 rounded mb-4 mt-2 hidden"></div>
        </form>
        <div class="mt-2 text-center text-sm">
            Already have an account? <a href="/login" class="text-blue-500 hover:underline">Sign in</a>
        </div>
    </div>
    <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.getElementById('registerDriverForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('first_name', document.getElementById('first_name').value);
        formData.append('last_name', document.getElementById('last_name').value);
        formData.append('email', document.getElementById('email').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('password', document.getElementById('password').value);
        formData.append('password_confirmation', document.getElementById('password_confirmation').value);
        formData.append('license_number', document.getElementById('license_number').value);
        formData.append('license_expiry', document.getElementById('license_expiry').value);
        formData.append('vehicle_model', document.getElementById('vehicle_model').value);
        formData.append('vehicle_year', document.getElementById('vehicle_year').value);
        formData.append('vehicle_color', document.getElementById('vehicle_color').value);
        formData.append('license_plate', document.getElementById('license_plate').value);
        formData.append('vehicle_seats', document.getElementById('vehicle_seats').value);
        
        // Add vehicle photos
        const vehiclePhoto1 = document.getElementById('vehicle_photo_1').files[0];
        const vehiclePhoto2 = document.getElementById('vehicle_photo_2').files[0];
        const vehiclePhoto3 = document.getElementById('vehicle_photo_3').files[0];
        
        if (vehiclePhoto1) formData.append('vehicle_photo_1', vehiclePhoto1);
        if (vehiclePhoto2) formData.append('vehicle_photo_2', vehiclePhoto2);
        if (vehiclePhoto3) formData.append('vehicle_photo_3', vehiclePhoto3);
        
        const errorDiv = document.getElementById('registerDriverError');
        errorDiv.classList.add('hidden');
        errorDiv.textContent = '';
        
        try {
            const response = await fetch('/api/register-driver', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            const data = await response.json();
            if (data.success && data.user && data.user.id) {
                localStorage.setItem('driver_user_id', data.user.id);
                window.location.href = '/register/driver-docs';
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
</body>
</html> 