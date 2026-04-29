<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/output.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Unauthorized - School Events Tracker</title>

    <script>
        setTimeout(() => {
            window.location.href = "login.php";
        }, 5000);
    </script>
</head>
<body class="font-body bg-gradient-to-br from-ust-light-bg via-ust-cream to-white flex items-center justify-center min-h-screen px-4">

    <div class="bg-white shadow-ust-md rounded-lg p-8 text-center max-w-md w-full border border-gray-100">
        
        <div class="inline-block mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-lock text-red-600 text-2xl"></i>
            </div>
        </div>

        <h1 class="text-3xl font-heading font-bold text-ust-dark mb-3">
            Access Denied
        </h1>

        <p class="text-ust-gray mb-6 text-sm leading-relaxed">
            You do not have permission to access this page or perform this action. Please contact your administrator if you believe this is an error.
        </p>

        <div class="space-y-3">
            <a href="login.php" 
               class="block w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark py-3 rounded-lg font-semibold transition shadow-ust">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Return to Login
            </a>
            <a href="home.php"
               class="block w-full border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-home mr-2"></i>
                Go to Home
            </a>
        </div>

        <p class="text-xs text-ust-gray mt-6">
            <i class="fas fa-clock mr-1"></i>
            Redirecting to login in 5 seconds...
        </p>

    </div>

</body>
</html>
