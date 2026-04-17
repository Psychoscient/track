<?php
    session_start();
    
    require_once "../bl/UserManager.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
</head>

<body class="min-h-screen flex flex-col bg-linear-to-br from-indigo-100 via-white to-blue-100">

    <!-- Main Content -->
    <div class="grow flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-800">Welcome Back</h1>
                    <p class="text-sm text-gray-500 mt-2">Login to your account to continue</p>
                </div>

                <div id="loginForm" class="space-y-5">

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            placeholder="Enter your email"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                Password
                            </label>
                            <a href="#" class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                                Forgot password?
                            </a>
                        </div>

                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Enter your password"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                        >
                    </div>

                    <button 
                        id="submit"
                        type="submit" 
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl shadow-md transition duration-200"
                    >
                        Log In
                    </button>

                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
                            Don’t have an account?
                            <a href="signup.php" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                Sign up
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center py-6 border-t border-gray-200 bg-white/70 backdrop-blur-sm">
        <p class="text-sm font-medium text-gray-700">
            © <?php echo date("Y"); ?> School Events Tracker
        </p>
        <p class="text-xs text-gray-500 mt-1">
            Empowering student engagement and event management
        </p>
    </footer>

    <script src="../script/utils.js"></script>
    <script src="../script/login.js"></script>
</body>
</html>