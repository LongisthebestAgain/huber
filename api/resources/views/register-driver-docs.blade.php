<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Hubber - Upload Documents</title>
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
                <span class="w-4 h-4 bg-blue-400 rounded-full flex items-center justify-center text-white text-xs">&#10003;</span>
                <span class="w-4 h-4 bg-blue-600 rounded-full flex items-center justify-center text-white text-xs">3</span>
            </div>
        </div>
        
        <div id="uploadError" class="bg-red-100 text-red-700 p-3 rounded mb-4 hidden"></div>
        <div id="uploadSuccess" class="bg-green-100 text-green-700 p-3 rounded mb-4 hidden"></div>
        
        <form id="driverDocsForm" enctype="multipart/form-data">
            <h3 class="text-lg font-semibold mb-2 text-center">Upload Documents</h3>
            <p class="text-gray-500 text-sm mb-4 text-center">Please upload the required documents for driver verification</p>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Driver's License</label>
                <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition-colors" id="driver_license_label">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4h6v4a1 1 0 01-1 1z" /></svg>
                    <span class="text-xs text-gray-500" id="driver_license_text">Click to upload driver's license<br>JPG, PNG, PDF (max 2MB)</span>
                    <input type="file" name="driver_license_file" id="driver_license_file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" required>
                </label>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Vehicle Registration</label>
                <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition-colors" id="vehicle_registration_label">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4h6v4a1 1 0 01-1 1z" /></svg>
                    <span class="text-xs text-gray-500" id="vehicle_registration_text">Click to upload vehicle registration<br>JPG, PNG, PDF (max 2MB)</span>
                    <input type="file" name="vehicle_registration_file" id="vehicle_registration_file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" required>
                </label>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Insurance Certificate</label>
                <label class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition-colors" id="insurance_certificate_label">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12m-4 4h-4a1 1 0 01-1-1v-4h6v4a1 1 0 01-1 1z" /></svg>
                    <span class="text-xs text-gray-500" id="insurance_certificate_text">Click to upload insurance certificate<br>JPG, PNG, PDF (max 2MB)</span>
                    <input type="file" name="insurance_certificate_file" id="insurance_certificate_file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" required>
                </label>
            </div>
            
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="terms_accepted" name="terms_accepted" class="mr-2" required>
                <label for="terms_accepted" class="text-xs text-gray-700">I agree to the <a href="#" class="text-blue-500 underline">Terms of Service</a> and <a href="#" class="text-blue-500 underline">Privacy Policy</a></label>
            </div>
            
            <input type="hidden" name="user_id" id="user_id" value="">
            
            <div class="flex justify-between items-center mb-4">
                <button type="button" onclick="window.history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition-colors">Back</button>
                <button type="submit" id="submitBtn" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition-colors flex items-center">
                    <span id="submitText">Create Account</span>
                    <svg id="loadingSpinner" class="hidden animate-spin ml-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>
        
        <div class="mt-2 text-center text-sm">
            Already have an account? <a href="/login" class="text-blue-500 hover:underline">Sign in</a>
        </div>
    </div>
    
    <script>
    // Set user_id from localStorage
    document.getElementById('user_id').value = localStorage.getItem('driver_user_id');
    
    // Check if user_id exists
    if (!localStorage.getItem('driver_user_id')) {
        document.getElementById('uploadError').textContent = 'User ID not found. Please complete the previous step.';
        document.getElementById('uploadError').classList.remove('hidden');
        document.getElementById('submitBtn').disabled = true;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // File input change handlers
    function setupFileInput(inputId, labelId, textId) {
        const input = document.getElementById(inputId);
        const label = document.getElementById(labelId);
        const text = document.getElementById(textId);
        
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB = 2 * 1024 * 1024 bytes)
                if (file.size > 2 * 1024 * 1024) {
                    text.innerHTML = '<span class="text-red-500">File too large! Max 2MB</span>';
                    label.classList.add('border-red-400');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    text.innerHTML = '<span class="text-red-500">Invalid file type! Use JPG, PNG, or PDF</span>';
                    label.classList.add('border-red-400');
                    input.value = '';
                    return;
                }
                
                // Show success state
                text.innerHTML = `<span class="text-green-600 font-medium">✓ ${file.name}</span>`;
                label.classList.remove('border-red-400');
                label.classList.add('border-green-400');
            }
        });
    }
    
    // Setup all file inputs
    setupFileInput('driver_license_file', 'driver_license_label', 'driver_license_text');
    setupFileInput('vehicle_registration_file', 'vehicle_registration_label', 'vehicle_registration_text');
    setupFileInput('insurance_certificate_file', 'insurance_certificate_label', 'insurance_certificate_text');
    
    // Form submission
    document.getElementById('driverDocsForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const errorDiv = document.getElementById('uploadError');
        const successDiv = document.getElementById('uploadSuccess');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const loadingSpinner = document.getElementById('loadingSpinner');
        
        // Hide previous messages
        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Uploading...';
        loadingSpinner.classList.remove('hidden');
        
        try {
            const response = await fetch('/api/register-driver-docs', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                successDiv.textContent = data.message || 'Documents uploaded successfully!';
                successDiv.classList.remove('hidden');
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
            } else {
                errorDiv.textContent = data.message || 'Upload failed. Please try again.';
                errorDiv.classList.remove('hidden');
                
                // Reset button state
                submitBtn.disabled = false;
                submitText.textContent = 'Create Account';
                loadingSpinner.classList.add('hidden');
            }
        } catch (err) {
            errorDiv.textContent = 'An error occurred. Please check your connection and try again.';
            errorDiv.classList.remove('hidden');
            
            // Reset button state
            submitBtn.disabled = false;
            submitText.textContent = 'Create Account';
            loadingSpinner.classList.add('hidden');
        }
    });
    </script>
</body>
</html> 