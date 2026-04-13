<?php
    session_start();

    require_once "../bl/PermissionManager.php";

    $permissionManager = new PermissionManager();
    $permissions = $permissionManager -> getPermissions();

    if (!isset($_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="./dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-indigo-600">School Events Tracker</h1>
            <nav class="space-x-6 hidden md:block">
                <a href="#" class="text-gray-600 hover:text-indigo-600">Home</a>
                <a href="#" class="text-gray-600 hover:text-indigo-600">Events</a>
                <a href="#" class="text-gray-600 hover:text-indigo-600">About</a>
                <a href="#" class="text-gray-600 hover:text-indigo-600">Contact</a>
                <button id="logout" class="text-white-600 rounded-lg bg-blue-500 p-2 hover:text-white-600">Logout</button>
                <?php
                    if (in_array('manage_users', $_SESSION['permissions'])) {
                        echo '<button id="dashboardBtn" class="text-white-600 rounded-lg bg-blue-500 p-2 hover:text-white-600">Dashboard</button>';
                    }
                ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-20 text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">
                Welcome to the School Events Tracker
            </h2>
            <p class="text-lg md:text-xl text-indigo-100 max-w-2xl mx-auto mb-8">
                Stay updated with upcoming school programs, activities, and important events all in one place.
            </p>
            <div class="flex justify-center gap-4">
                <a href="#" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100">
                    View Events
                </a>
                <a href="#" class="border border-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <h3 class="text-3xl font-bold text-center mb-12">What You Can Do</h3>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h4 class="text-xl font-semibold mb-3 text-indigo-600">Track Events</h4>
                <p class="text-gray-600">
                    View all upcoming school events in a single organized platform.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h4 class="text-xl font-semibold mb-3 text-indigo-600">Stay Informed</h4>
                <p class="text-gray-600">
                    Get quick access to schedules, announcements, and important updates.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <h4 class="text-xl font-semibold mb-3 text-indigo-600">Easy Access</h4>
                <p class="text-gray-600">
                    Navigate through the homepage with a simple and student-friendly interface.
                </p>
            </div>
        </div>
    </section>

    <!-- Announcement Section -->
    <section class="bg-white py-16">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold mb-4">Latest Announcement</h3>
            <p class="text-gray-600 mb-6">
                Welcome to the platform. This homepage is currently for design purposes only and does not have working functionality yet.
            </p>
            <a href="#" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700">
                Read More
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-6">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p>&copy; 2026 School Events Tracker. All rights reserved.</p>
        </div>
    </footer>

    <script src="../script/utils.js"></script>
    <script src="../script/main.js"></script>
</body>
</html>