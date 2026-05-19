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
    <link rel="stylesheet" href="../public/output.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../script/swal-theme.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Login - TRACK</title>
</head>

<body class="relative isolate min-h-screen flex flex-col font-body bg-gradient-to-br from-ust-light-bg via-ust-cream to-white">
    <?php require __DIR__ . '/partials/auth-background.php'; ?>

    <!-- Main Content -->
    <div class="relative z-10 grow flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="mb-8 flex justify-center">
                <?php $logoVariant = 'navbar'; require __DIR__ . '/partials/logo.php'; ?>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-ust-md rounded-lg p-8 border border-gray-100">

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-heading font-semibold text-ust-dark">Welcome Back</h2>
                    <p class="text-sm text-ust-gray mt-2">Login to your account to continue</p>
                </div>

                <div id="loginForm" class="space-y-6">

                    <div>
                        <label for="email" class="block text-sm font-semibold text-ust-dark mb-2">
                            Email Address <span class="text-red-600" aria-hidden="true">*</span>
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            placeholder="Enter your email"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-ust-dark">
                                Password <span class="text-red-600" aria-hidden="true">*</span>
                            </label>
                            <a href="forgot-password.php" class="text-xs text-ust-gold hover:text-ust-gold-dark font-medium transition">
                                Forgot password?
                            </a>
                        </div>

                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Enter your password"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <button 
                        id="submit"
                        type="submit" 
                        class="w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-lg shadow-ust transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-sign-in-alt"></i>
                        Log In
                    </button>

                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="px-2 bg-white text-ust-gray">or</span>
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-sm text-ust-gray mb-3">
                            Don't have an account?
                        </p>
                        <a href="signup.php" class="inline-block px-6 py-2 border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 font-semibold rounded-lg transition">
                            Create Account
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php $showDashboardLink = false; require __DIR__ . '/partials/footer.php'; ?>

    <script src="../script/utils.js"></script>
    <script src="../script/login.js"></script>
</body>
</html>
