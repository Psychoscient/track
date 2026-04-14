<?php
    // session_start();

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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Signup</title>
</head>

<body class="min-h-screen flex flex-col bg-gradient-to-br from-indigo-100 via-white to-purple-100">

    <!-- Main Content -->
    <div class="grow flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg">
            <div class="bg-white/90 backdrop-blur-md shadow-2xl rounded-2xl p-8 border border-gray-100">
                
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-800">Create an Account</h1>
                    <p class="text-sm text-gray-500 mt-2">Sign up to access the system</p>
                </div>

                <div id="signupForm" class="space-y-5">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="fname" class="block text-sm font-semibold text-gray-700 mb-1">
                                First Name
                            </label>
                            <input 
                                type="text" 
                                id="fname" 
                                name="fname" 
                                required
                                placeholder="Enter first name"
                                data-type="fname"
                                class="input-field w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                            >
                        </div>

                        <div>
                            <label for="lname" class="block text-sm font-semibold text-gray-700 mb-1">
                                Surname
                            </label>
                            <input 
                                type="text" 
                                id="lname" 
                                name="lname" 
                                required
                                placeholder="Enter surname"
                                data-type="fname"
                                class="input-field w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                            >
                        </div>
                    </div>

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
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                            Password
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            placeholder="Create a password"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                        >
                    </div>

                    <div>
                        <label for="yearlvl" class="block text-sm font-semibold text-gray-700 mb-1">
                            Year Level
                        </label>
                        <select 
                            id="yearlvl" 
                            name="yearlvl" 
                            required
                            class="input-field w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
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
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl shadow-md transition duration-200"
                    >
                        Sign Up
                    </button>

                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="login.php" class="text-indigo-600 hover:text-indigo-700 font-semibold">
                                Log in
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
    <script src="../script/signup.js"></script>
</body>
</html>