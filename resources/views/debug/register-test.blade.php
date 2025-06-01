<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Debug</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Debug Registration Form</h1>
        
        <form id="debug-form" method="POST" action="/register" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" id="debug-email" class="w-full border border-gray-300 rounded p-2" required>
            </div>
            
            <div class="bg-blue-50 p-4 rounded mb-4">
                <h3 class="font-bold">Debug Info:</h3>
                <div id="debug-output" class="text-sm"></div>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Register (Debug Mode)</button>
        </form>
        
        <!-- Direct Form Method -->
        <div class="mt-8 pt-4 border-t">
            <h2 class="font-bold mb-4">Alternative Form</h2>
            <form action="/register" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="email" name="email" placeholder="Your email" class="w-full border border-gray-300 rounded p-2 mb-4">
                <button type="submit" class="w-full bg-green-500 text-white p-2 rounded">Register (Direct)</button>
            </form>
        </div>
        
        <!-- Fetch API Method -->
        <div class="mt-8 pt-4 border-t">
            <h2 class="font-bold mb-4">API Method</h2>
            <input type="email" id="api-email" placeholder="Your email" class="w-full border border-gray-300 rounded p-2 mb-4">
            <button id="api-submit" class="w-full bg-purple-500 text-white p-2 rounded">Register (API)</button>
            <div id="api-result" class="mt-4 text-sm"></div>
        </div>
    </div>

    <script>
        // Debug Form
        document.getElementById('debug-form').addEventListener('submit', function(e) {
            const email = document.getElementById('debug-email').value;
            const debugOutput = document.getElementById('debug-output');
            
            debugOutput.innerHTML += `<p>Submitting email: ${email}</p>`;
            debugOutput.innerHTML += `<p>CSRF token: ${document.querySelector('input[name="_token"]').value}</p>`;
            
            // Don't prevent default - let the form submit normally
        });
        
        // API Method
        document.getElementById('api-submit').addEventListener('click', function() {
            const email = document.getElementById('api-email').value;
            const resultDiv = document.getElementById('api-result');
            const token = document.querySelector('input[name="_token"]').value;
            
            resultDiv.innerHTML = "Sending request...";
            
            fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    email: email,
                    _token: token
                })
            })
            .then(response => {
                resultDiv.innerHTML += `<p>Status: ${response.status}</p>`;
                if (response.redirected) {
                    resultDiv.innerHTML += `<p>Redirected to: ${response.url}</p>`;
                }
                return response.text();
            })
            .then(data => {
                resultDiv.innerHTML += `<p>Response (first 100 chars): ${data.substring(0, 100)}...</p>`;
            })
            .catch(error => {
                resultDiv.innerHTML += `<p>Error: ${error.message}</p>`;
            });
        });
    </script>
</body>
</html>
