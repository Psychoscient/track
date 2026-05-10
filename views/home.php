<?php
    session_start();

    require_once "../bl/UserManager.php";
    require_once "../bl/RoleManager.php";
    require_once "../bl/OrganizerApplicationManager.php";

    if (!isset($_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

    $userManager = new UserManager();
    $roleManager = new RoleManager();
    $organizerApplicationManager = new OrganizerApplicationManager();

    $currentUser = $userManager -> getUser("user_id", $_SESSION['user_id']);
    if ($currentUser && (!isset($currentUser['status']) || $currentUser['status'] !== false)) {
        $permissions = array_column($roleManager -> getPermissions($currentUser['role_id']), 'permission_name');
        $_SESSION['role_id'] = $currentUser['role_id'];
        $_SESSION['permissions'] = $permissions;
    }

    $isRegularUser = (int)$_SESSION['role_id'] === 2;
    $latestOrganizerApplication = $organizerApplicationManager -> getLatestApplicationByUser($_SESSION['user_id']);
    $hasLatestOrganizerApplication = $latestOrganizerApplication && (!isset($latestOrganizerApplication['status']) || $latestOrganizerApplication['status'] !== false);
    $latestOrganizerApplicationStatus = $hasLatestOrganizerApplication
        ? $latestOrganizerApplication['organizer_application_status_name']
        : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - School Events Tracker</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../public/output.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="font-body bg-ust-light-bg text-ust-dark">

    <!-- Navbar -->
    <header class="bg-white shadow-ust border-b-4 border-ust-gold">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-ust-gold rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-ust-dark text-lg"></i>
                </div>
                <h1 class="text-2xl font-heading font-bold text-ust-dark">School Events Tracker</h1>
            </div>
            <nav class="space-x-6 hidden md:flex items-center">
                <a href="home.php" class="text-ust-gold font-semibold transition">Home</a>
                <a href="events.php" class="text-ust-dark hover:text-ust-gold font-medium transition">Events</a>
                <a href="#" class="text-ust-dark hover:text-ust-gold font-medium transition">About</a>
                <a href="#" class="text-ust-dark hover:text-ust-gold font-medium transition">Contact</a>
                <button id="logout" class="text-white rounded-lg bg-ust-gold hover:bg-ust-gold-dark px-4 py-2 font-semibold transition">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </button>
                <?php
                    if (in_array('manage_users', $_SESSION['permissions'])) {
                        echo '<a href="dashboard.php" class="text-white rounded-lg bg-ust-dark hover:bg-ust-gray px-4 py-2 font-semibold transition inline-flex items-center">
                                <i class="fas fa-chart-line mr-2"></i>Dashboard
                            </a>';
                    }
                ?>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-ust-dark to-ust-gray text-white py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h2 class="text-5xl font-heading font-bold mb-4">
                Welcome to School Events Tracker
            </h2>
            <p class="text-lg text-gray-200 max-w-2xl mx-auto mb-8">
                Stay informed with upcoming school programs, activities, and important events all in one elegant platform.
            </p>
            <div class="flex justify-center gap-4">
                <a href="events.php" class="bg-ust-gold text-ust-dark px-8 py-3 rounded-lg font-semibold shadow-ust hover:bg-ust-gold-dark transition">
                    <i class="fas fa-calendar-alt mr-2"></i>View Events
                </a>
                <a href="#" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-ust-dark transition">
                    <i class="fas fa-book mr-2"></i>Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="max-w-7xl mx-auto px-6 py-16">
        <h3 class="text-3xl font-heading font-bold text-center mb-12 text-ust-dark">What You Can Do</h3>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-lg shadow-ust hover:shadow-ust-md transition border-t-4 border-ust-gold">
                <div class="w-12 h-12 bg-ust-gold/10 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-calendar text-ust-gold text-xl"></i>
                </div>
                <h4 class="text-xl font-heading font-semibold mb-3 text-ust-dark">Track Events</h4>
                <p class="text-ust-gray">
                    View all upcoming school events in a single organized platform. Stay updated with dates, times, and important details.
                </p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-ust hover:shadow-ust-md transition border-t-4 border-ust-gold">
                <div class="w-12 h-12 bg-ust-gold/10 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-bell text-ust-gold text-xl"></i>
                </div>
                <h4 class="text-xl font-heading font-semibold mb-3 text-ust-dark">Stay Informed</h4>
                <p class="text-ust-gray">
                    Get quick access to schedules, announcements, and important updates about all school activities and programs.
                </p>
            </div>

            <div class="bg-white p-8 rounded-lg shadow-ust hover:shadow-ust-md transition border-t-4 border-ust-gold">
                <div class="w-12 h-12 bg-ust-gold/10 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-mobile-alt text-ust-gold text-xl"></i>
                </div>
                <h4 class="text-xl font-heading font-semibold mb-3 text-ust-dark">Easy Access</h4>
                <p class="text-ust-gray">
                    Navigate through the platform with an intuitive, student-friendly interface. Accessible on all your devices.
                </p>
            </div>
        </div>
    </section>

    <?php if ($isRegularUser) : ?>
        <section class="max-w-7xl mx-auto px-6 pb-16">
            <div class="bg-white shadow-ust-md rounded-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-5 border-b-4 border-ust-gold">
                    <div class="flex items-center justify-between gap-4 flex-wrap">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-ust-gold">Organizer Path</p>
                            <h3 class="text-2xl font-heading font-bold text-white mt-2">Apply to Become an Organizer</h3>
                        </div>
                        <?php if ($hasLatestOrganizerApplication) : ?>
                            <span class="inline-flex items-center rounded-full px-4 py-2 text-xs font-semibold
                                <?php
                                    if ($latestOrganizerApplicationStatus === 'pending') {
                                        echo 'bg-amber-100 text-amber-700';
                                    } else if ($latestOrganizerApplicationStatus === 'approved') {
                                        echo 'bg-emerald-100 text-emerald-700';
                                    } else {
                                        echo 'bg-red-100 text-red-700';
                                    }
                                ?>">
                                <?= strtoupper(htmlspecialchars($latestOrganizerApplicationStatus)) ?>
                            </span>
                        <?php else : ?>
                            <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-white">
                                <i class="fas fa-file-signature text-ust-gold mr-2"></i>
                                Open for Application
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="p-6 md:p-8">
                    <div class="grid lg:grid-cols-[0.95fr_1.05fr] gap-8 items-start">
                        <div class="rounded-2xl bg-ust-cream border border-ust-gold/10 p-6">
                            <h4 class="text-xl font-heading font-bold text-ust-dark">What organizers can do</h4>
                            <p class="mt-3 text-sm text-ust-gray leading-7">
                                Approved organizers gain event-management access and can create, update, and manage the events they own in the shared events workspace.
                            </p>
                            <div class="mt-6 space-y-3 text-sm text-ust-gray">
                                <div class="flex items-start gap-3">
                                    <span class="mt-1 text-ust-gold"><i class="fas fa-check-circle"></i></span>
                                    <p>Create and publish organization events</p>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="mt-1 text-ust-gold"><i class="fas fa-check-circle"></i></span>
                                    <p>Manage only the events you created</p>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="mt-1 text-ust-gold"><i class="fas fa-check-circle"></i></span>
                                    <p>Wait for admin review before organizer access is granted</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-gray-100 bg-white">
                            <div class="p-6">
                                <?php if (!$hasLatestOrganizerApplication || $latestOrganizerApplicationStatus === 'rejected') : ?>
                                    <h4 class="text-xl font-heading font-bold text-ust-dark">Submit your application</h4>
                                    <p class="mt-3 text-sm text-ust-gray leading-7">
                                        Tell the admins why you should receive organizer access. Keep it clear and specific so they can review it quickly.
                                    </p>

                                    <?php if ($latestOrganizerApplicationStatus === 'rejected') : ?>
                                        <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                                            Your latest organizer application was rejected. You can submit a new application below.
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-6">
                                        <label for="organizerReason" class="block text-sm font-semibold text-ust-dark mb-2">
                                            Reason for applying
                                        </label>
                                        <textarea
                                            id="organizerReason"
                                            rows="6"
                                            class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream resize-none"
                                            placeholder="Explain why you want to become an organizer and what events or responsibilities you plan to handle."
                                        ></textarea>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button
                                            id="applyOrganizerBtn"
                                            type="button"
                                            class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark shadow-ust transition duration-200 flex items-center justify-center gap-2"
                                        >
                                            <i class="fas fa-paper-plane"></i>
                                            Submit Application
                                        </button>
                                    </div>
                                <?php else : ?>
                                    <h4 class="text-xl font-heading font-bold text-ust-dark">Current application status</h4>
                                    <p class="mt-3 text-sm text-ust-gray leading-7">
                                        Your latest organizer application is currently being tracked below.
                                    </p>

                                    <div class="mt-6 space-y-5">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-ust-gray">Status</p>
                                            <p class="mt-2 text-lg font-heading font-bold text-ust-dark">
                                                <?= ucfirst(htmlspecialchars($latestOrganizerApplicationStatus)) ?>
                                            </p>
                                        </div>

                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-ust-gray">Submitted reason</p>
                                            <div class="mt-2 rounded-xl bg-ust-cream border border-ust-gold/10 p-4 text-sm text-ust-gray leading-7">
                                                <?= nl2br(htmlspecialchars($latestOrganizerApplication['reason'])) ?>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-xs uppercase tracking-[0.2em] text-ust-gray">Last updated</p>
                                            <p class="mt-2 text-sm font-semibold text-ust-dark">
                                                <?= date('M d, Y g:i A', strtotime($latestOrganizerApplication['updated_at'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Announcement Section -->
    <section class="bg-white py-16 border-y-4 border-ust-gold">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="inline-block px-4 py-2 bg-ust-gold/10 rounded-full mb-4">
                <span class="text-sm font-semibold text-ust-gold">Latest News</span>
            </div>
            <h3 class="text-3xl font-heading font-bold mb-4 text-ust-dark">Latest Announcement</h3>
            <p class="text-ust-gray mb-6 leading-relaxed">
                Welcome to the School Events Tracker platform. This is your central hub for all academic events, ceremonies, and student activities. Keep checking back for regular updates on upcoming events and important dates.
            </p>
            <a href="#" class="inline-block bg-ust-gold text-ust-dark px-8 py-3 rounded-lg font-semibold hover:bg-ust-gold-dark shadow-ust transition">
                <i class="fas fa-arrow-right mr-2"></i>Read More
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-ust-dark text-white py-8">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-8 mb-8">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-ust-gold rounded flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-ust-dark text-sm"></i>
                        </div>
                        <h4 class="font-heading font-bold">School Events Tracker</h4>
                    </div>
                    <p class="text-gray-300 text-sm">Empowering student engagement and event management.</p>
                </div>
                <div>
                    <h5 class="font-semibold mb-4 text-ust-gold">Quick Links</h5>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><a href="home.php" class="hover:text-ust-gold transition">Home</a></li>
                        <li><a href="events.php" class="hover:text-ust-gold transition">Events</a></li>
                        <li><a href="#" class="hover:text-ust-gold transition">About</a></li>
                    </ul>
                </div>
                <div>
                    <h5 class="font-semibold mb-4 text-ust-gold">Contact</h5>
                    <ul class="space-y-2 text-sm text-gray-300">
                        <li><i class="fas fa-envelope mr-2"></i>info@school.edu</li>
                        <li><i class="fas fa-phone mr-2"></i>+1 (555) 123-4567</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>School Address</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-6 text-center text-sm text-gray-300">
                <p>&copy; <?php echo date("Y"); ?> School Events Tracker. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../script/utils.js"></script>
    <script src="../script/main.js"></script>
</body>
</html>
