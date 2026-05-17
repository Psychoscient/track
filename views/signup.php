<?php
    require_once "../bl/UserManager.php";
    require_once "../bl/YearLevelManager.php";

    $yearlvlmanager = new YearLevelManager();
    $yearlvl = $yearlvlmanager->getYearLevel();
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
    <title>Sign Up - TRACK</title>
</head>

<body class="relative isolate min-h-screen flex flex-col font-body bg-gradient-to-br from-ust-light-bg via-ust-cream to-white">
    <?php require __DIR__ . '/partials/auth-background.php'; ?>

    <!-- Main Content -->
    <div class="relative z-10 grow flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg">
            <div class="mb-8 flex justify-center">
                <?php $logoVariant = 'navbar'; require __DIR__ . '/partials/logo.php'; ?>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-ust-md rounded-lg p-8 border border-gray-100">
                
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-heading font-semibold text-ust-dark">Create an Account</h2>
                    <p class="text-sm text-ust-gray mt-2">Sign up to access the system</p>
                </div>

                <div id="signupForm" class="space-y-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fname" class="block text-sm font-semibold text-ust-dark mb-2">
                                First Name
                            </label>
                            <input 
                                type="text" 
                                id="fname" 
                                name="fname" 
                                required
                                placeholder="Enter first name"
                                data-type="fname"
                                class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                            >
                        </div>

                        <div>
                            <label for="lname" class="block text-sm font-semibold text-ust-dark mb-2">
                                Surname
                            </label>
                            <input 
                                type="text" 
                                id="lname" 
                                name="lname" 
                                required
                                placeholder="Enter surname"
                                data-type="fname"
                                class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                            >
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-ust-dark mb-2">
                            Email Address
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
                        <label for="password" class="block text-sm font-semibold text-ust-dark mb-2">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Create a password"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div>
                        <label for="confirmPassword" class="block text-sm font-semibold text-ust-dark mb-2">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirmPassword"
                            required
                            placeholder="Confirm your password"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div>
                        <label for="yearlvl" class="block text-sm font-semibold text-ust-dark mb-2">
                            Year Level
                        </label>
                        <select 
                            id="yearlvl" 
                            name="yearlvl" 
                            required
                            class="input-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm font-body text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition"
                        >
                            <option value="">Choose Year Level</option>
                            <?php foreach ($yearlvl as $year): ?>
                                <option value="<?= $year['year_lvl_id'] ?>">
                                    <?= $year['year_lvl_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button
                        id="submit"
                        type="submit" 
                        class="w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-lg shadow-ust transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-user-plus"></i>
                        Create Account
                    </button>

                    <div class="relative my-4">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-xs uppercase">
                            <span class="px-2 bg-white text-ust-gray">or</span>
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <p class="text-sm text-ust-gray">
                            Already have an account?
                        </p>
                        <a href="login.php" class="inline-block px-6 py-2 border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 font-semibold rounded-lg transition mt-3">
                            Log In
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php $showDashboardLink = false; require __DIR__ . '/partials/footer.php'; ?>

    <script src="../script/utils.js?v=20260517"></script>
    <script src="../script/signup.js?v=20260517"></script>
</body>
</html>
