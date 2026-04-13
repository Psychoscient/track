<?php
    session_start();

    // optional: if already logged in, you can redirect somewhere else
    // but for now we just show the page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>

    <!-- Tailwind -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

    <!-- Optional auto redirect -->
    <script>
        setTimeout(() => {
            window.location.href = "login.php";
        }, 5000); // 5 seconds
    </script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white shadow-lg rounded-xl p-8 text-center max-w-md w-full">
        
        <h1 class="text-3xl font-bold text-red-600 mb-4">
            Unauthorized
        </h1>

        <p class="text-gray-600 mb-6">
            You do not have permission to access this page or perform this action.
        </p>

        <div class="space-y-3">
            <a href="login.php" 
               class="block w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Go to Login
            </a>

            <a href="dashboard.php" 
               class="block w-full bg-gray-300 text-gray-800 py-2 rounded-lg hover:bg-gray-400 transition">
                Back to Dashboard
            </a>
        </div>

        <p class="text-sm text-gray-400 mt-4">
            Redirecting to login in 5 seconds...
        </p>

    </div>

</body>
</html>