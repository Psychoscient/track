<?php
    session_start();
    require_once "../bl/UserManager.php";
    require_once "../bl/YearLevelManager.php";
    require_once "../bl/RoleManager.php";
    require_once "../bl/OrganizerApplicationManager.php";

    $users = new UserManager();
    $usersDetails = $users -> getUsersWithRelations();

    $totalUsers = $users -> getTotalUsers();

    $yearlvlmanager = new YearLevelManager();
    $yearlvl = $yearlvlmanager -> getYearLevel();

    $rolemanager = new RoleManager();
    $roles = $rolemanager -> getRoles();

    $organizerApplicationManager = new OrganizerApplicationManager();
    $pendingApplications = $organizerApplicationManager -> getPendingApplications();

    if (!isset($_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

    if (!in_array('manage_users', $_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

    $roleChartData = [];
    foreach ($roles as $role) {
        $roleChartData[$role['role_name']] = 0;
    }

    $yearLevelChartData = [];
    $pendingApplicationChartData = [];
    foreach ($yearlvl as $year) {
        $yearLevelChartData[$year['year_lvl_name']] = 0;
        $pendingApplicationChartData[$year['year_lvl_name']] = 0;
    }

    $growthChartData = [];
    for ($monthOffset = 5; $monthOffset >= 0; $monthOffset--) {
        $monthKey = date('Y-m', strtotime("first day of -{$monthOffset} months"));
        $growthChartData[$monthKey] = [
            'label' => date('M Y', strtotime($monthKey . '-01')),
            'count' => 0
        ];
    }

    $updateFreshnessChartData = [
        'Updated this week' => 0,
        'Updated this month' => 0,
        'Older updates' => 0
    ];

    $newUsersThisMonth = 0;
    $updatedThisWeek = 0;
    $currentMonth = date('Y-m');
    $oneWeekAgo = strtotime('-7 days');
    $firstDayOfMonth = strtotime(date('Y-m-01 00:00:00'));

    foreach ($usersDetails as $user) {
        $roleName = $user['role_name'] ?? 'Unassigned';
        if (!isset($roleChartData[$roleName])) {
            $roleChartData[$roleName] = 0;
        }
        $roleChartData[$roleName]++;

        $yearLevelName = $user['year_lvl_name'] ?? 'Unassigned';
        if (!isset($yearLevelChartData[$yearLevelName])) {
            $yearLevelChartData[$yearLevelName] = 0;
        }
        $yearLevelChartData[$yearLevelName]++;

        $createdAt = strtotime($user['user_created_at'] ?? '');
        if ($createdAt) {
            $createdMonth = date('Y-m', $createdAt);
            if (isset($growthChartData[$createdMonth])) {
                $growthChartData[$createdMonth]['count']++;
            }
            if ($createdMonth === $currentMonth) {
                $newUsersThisMonth++;
            }
        }

        $updatedAt = strtotime($user['user_updated_at'] ?? '');
        if ($updatedAt >= $oneWeekAgo) {
            $updateFreshnessChartData['Updated this week']++;
            $updatedThisWeek++;
        } elseif ($updatedAt >= $firstDayOfMonth) {
            $updateFreshnessChartData['Updated this month']++;
        } else {
            $updateFreshnessChartData['Older updates']++;
        }
    }

    foreach ($pendingApplications as $application) {
        $yearLevelName = $application['year_lvl_name'] ?? 'Unassigned';
        if (!isset($pendingApplicationChartData[$yearLevelName])) {
            $pendingApplicationChartData[$yearLevelName] = 0;
        }
        $pendingApplicationChartData[$yearLevelName]++;
    }

    $userAnalyticsData = [
        'roles' => $roleChartData,
        'yearLevels' => $yearLevelChartData,
        'growth' => array_values($growthChartData),
        'updates' => $updateFreshnessChartData,
        'pendingApplications' => $pendingApplicationChartData,
        'summary' => [
            'totalUsers' => (int) ($totalUsers['total_users'] ?? count($usersDetails)),
            'newUsersThisMonth' => $newUsersThisMonth,
            'updatedThisWeek' => $updatedThisWeek,
            'pendingApplications' => count($pendingApplications)
        ]
    ];

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>User Management - TRACK</title>
</head>
<body class="min-h-screen flex flex-col font-body bg-ust-light-bg">

    <?php
        $activePage = 'management';
        $showManagementLink = true;
        require __DIR__ . '/partials/navbar.php';
    ?>

    <div class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-6 px-6 py-8 lg:flex-row lg:items-start">
        <?php
            $managementArea = 'users';
            $managementSections = [
                ['id' => 'overview', 'label' => 'Overview', 'icon' => 'fa-chart-line'],
                ['id' => 'create-user', 'label' => 'Create User', 'icon' => 'fa-user-plus'],
                ['id' => 'users', 'label' => 'Users', 'icon' => 'fa-list'],
                ['id' => 'applications', 'label' => 'Applications', 'icon' => 'fa-file-signature'],
            ];
            require __DIR__ . '/partials/management-sidebar.php';
        ?>

        <main class="min-w-0 flex-1">
    <section id="overview" class="scroll-mt-6">
        <div class="mb-8">
        <h2 class="text-2xl font-heading font-bold text-ust-dark mb-6">User Management Overview</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-6">
            <!-- Card 1 - Total Users -->
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center border border-gray-100">
                <div class="text-4xl font-heading font-bold text-ust-gold mb-2">
                    <?= $totalUsers['total_users']; ?>
                </div>
                <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                    <i class="fas fa-users"></i>Total Users
                </div>
            </div>

            <!-- Card 2 - Admins -->
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center border border-gray-100">
                <div class="text-4xl font-heading font-bold text-ust-dark mb-2">
                    <?php 
                        $rolesDetails = array_column($usersDetails, 'role_id');
                        $adminCount = 0;

                        foreach($rolesDetails as $roleID) {
                            if ($roleID == 1) {
                                $adminCount++;
                            }
                        }

                        echo $adminCount;
                    ?>
                </div>
                <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                    <i class="fas fa-shield-alt"></i>Administrators
                </div>
            </div>

            <!-- Card 3 - Regular Users -->
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center border border-gray-100">
                <div class="text-4xl font-heading font-bold text-ust-dark mb-2">
                    <?php 
                        $rolesDetails = array_column($usersDetails, 'role_id');
                        $count = 0;

                        foreach($rolesDetails as $roleID) {
                            if ($roleID == 2) {
                                $count++;
                            }
                        }

                        echo $count;
                    ?>
                </div>
                <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                    <i class="fas fa-user"></i>Regular Users
                </div>
            </div>

            <!-- Card 4 - Organizers -->
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center border border-gray-100">
                <div class="text-4xl font-heading font-bold text-ust-dark mb-2">
                    <?php 
                        $rolesDetails = array_column($usersDetails, 'role_id');
                        $count = 0;

                        foreach($rolesDetails as $roleID) {
                            if ($roleID == 3) {
                                $count++;
                            }
                        }

                        echo $count;
                    ?>
                </div>
                <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i>Organizers
                </div>
            </div>

            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center border border-gray-100">
                <div class="text-4xl font-heading font-bold text-ust-dark mb-2">
                    <?= count($pendingApplications); ?>
                </div>
                <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                    <i class="fas fa-user-clock"></i>Pending Applications
                </div>
            </div>
        </div>
        <div class="mt-5 flex justify-end">
            <button
                type="button"
                onclick="openChartModal()"
                class="dashboard-btn inline-flex items-center justify-center gap-2 rounded-lg bg-ust-gold px-5 py-3 text-sm font-semibold text-white shadow-ust transition duration-200 hover:bg-ust-gold-dark"
            >
                <i class="fas fa-chart-pie"></i>
                View User Charts
            </button>
        </div>
        </div>
    </section>

    <div>
        <!-- Create User Panel -->
        <section id="create-user" class="scroll-mt-6 bg-white border border-gray-200 shadow-ust rounded-lg mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-4 border-b-4 border-ust-gold">
                <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user-plus"></i>Create New User
                </h3>
            </div>
            <div id="updateForm" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="fname" class="block text-sm font-semibold text-ust-dark mb-2">
                            First Name
                        </label>
                        <input
                            type="text"
                            id="fname"
                            name="fname"
                            maxlength="20"
                            required
                            placeholder="Enter first name"
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
                            maxlength="20"
                            required
                            placeholder="Enter surname"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-semibold text-ust-dark mb-2">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="Enter email address"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-ust-dark mb-2">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Enter password"
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
                                    <?php foreach($yearlvl as $index => $year) : ?>
                                        <option value="<?= $year['year_lvl_id'] ?>">
                                            <?= $year['year_lvl_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="role" class="block text-sm font-semibold text-ust-dark mb-2">
                                    Role
                                </label>
                                <select
                                    id="role"
                                    name="role"
                                    required
                                    class="input-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm font-body text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition"
                                >
                                    <option value="">Choose Role</option>
                                    <?php foreach($roles as $index => $role) : ?>
                                        <option value="<?= $role['role_id'] ?>">
                                            <?= $role['role_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button
                        id="create"
                        type="button"
                        class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark shadow-ust transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-plus"></i>
                        Create User
                    </button>
                    <button
                        type="button"
                        onclick="Utils.resetFields()"
                        class="px-6 py-3 rounded-lg border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 text-sm font-semibold transition duration-200"
                    >
                        Clear
                    </button>
                </div>
            </div>
        </section>

        <!-- Users Table -->
        <section id="users" class="scroll-mt-6 bg-white shadow-ust rounded-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-4 border-b-4 border-ust-gold">
                <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                    <i class="fas fa-list"></i>User Management
                </h3>
            </div>
            <div class="management-table-toolbar">
                <label for="userTableSearch" class="sr-only">Search users</label>
                <div class="management-table-search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input
                        id="userTableSearch"
                        type="search"
                        placeholder="Search users"
                        class="input-field"
                    >
                </div>
                <p id="userTableSummary" class="management-table-summary"></p>
            </div>
            <div class="management-table-wrap">
                <table id="userManagementTable" class="management-table management-table--users" data-page-size="10">
                    <thead class="bg-ust-light-bg border-b-2 border-ust-gold">
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>     
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Year Level</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody data-table-body>
                    <?php  
                        usort($usersDetails, function($a, $b) {
                            return $a['user_id'] <=> $b['user_id'];
                        });
                    ?>
                    <?php if (!empty($usersDetails)) : ?>
                        <?php foreach($usersDetails as $index => $user) : ?>
                            <tr class="border-b hover:bg-ust-cream/50 transition" data-table-row>
                                <td class="font-semibold text-ust-gold"><?= $user['user_id'] ?></td>
                                <td class="text-ust-dark"><?= $user['first_name'] ?></td>
                                <td class="text-ust-dark"><?= $user['last_name'] ?></td>
                                <td class="management-table__email text-ust-gray"><?= $user['email'] ?></td>
                                <td data-year-level-id="<?= $user['year_lvl_id'] ?? '' ?>">
                                    <span class="management-table__badge bg-ust-gold/10 text-ust-dark">
                                        <?= $user['year_lvl_name'] ?>
                                    </span>
                                </td>
                                <td data-role-id="<?= $user['role_id'] ?? '' ?>">
                                    <span class="management-table__badge bg-ust-gold/20 text-ust-dark">
                                        <?= $user['role_name'] ?>
                                    </span>
                                </td>
                                <td class="management-table__date text-ust-gray" title="<?= date('M j, Y g:i A', strtotime($user['user_created_at'])) ?>">
                                    <span><?= date('M j, Y', strtotime($user['user_created_at'])) ?></span>
                                    <span><?= date('g:i A', strtotime($user['user_created_at'])) ?></span>
                                </td>
                                <td class="management-table__date text-ust-gray" title="<?= date('M j, Y g:i A', strtotime($user['user_updated_at'])) ?>">
                                    <span><?= date('M j, Y', strtotime($user['user_updated_at'])) ?></span>
                                    <span><?= date('g:i A', strtotime($user['user_updated_at'])) ?></span>
                                </td>
                                <td>
                                    <div class="management-table__actions">
                                        <button
                                            id="editBtn" 
                                            type="button"
                                            class="dashboard-btn management-icon-btn bg-ust-gold hover:bg-ust-gold-dark"
                                            data-action="edit"
                                            data-userid="<?= $user['user_id'] ?>"
                                            aria-label="Edit user <?= $user['user_id'] ?>"
                                            title="Edit user"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button 
                                            id="deleteBtn"
                                            type="button"
                                            class="dashboard-btn management-icon-btn bg-red-500 hover:bg-red-600"
                                            data-action="delete"
                                            data-userid="<?= $user['user_id'] ?>"
                                            aria-label="Delete user <?= $user['user_id'] ?>"
                                            title="Delete user"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9" class="p-6 text-center text-ust-gray">
                                <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                No users found in the system.
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="userTablePagination" class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p data-pagination-status class="text-sm text-ust-gray"></p>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        data-pagination-prev
                        class="rounded-lg border-2 border-ust-gold px-4 py-2 text-sm font-semibold text-ust-gold transition hover:bg-ust-gold/5 disabled:cursor-not-allowed disabled:border-gray-200 disabled:text-gray-400 disabled:hover:bg-transparent"
                    >
                        Previous
                    </button>
                    <span data-pagination-pages class="min-w-[5rem] text-center text-sm font-semibold text-ust-dark"></span>
                    <button
                        type="button"
                        data-pagination-next
                        class="rounded-lg border-2 border-ust-gold px-4 py-2 text-sm font-semibold text-ust-gold transition hover:bg-ust-gold/5 disabled:cursor-not-allowed disabled:border-gray-200 disabled:text-gray-400 disabled:hover:bg-transparent"
                    >
                        Next
                    </button>
                </div>
            </div>
        </section>

        <section id="applications" class="scroll-mt-6 bg-white shadow-ust rounded-lg overflow-hidden border border-gray-100 mt-8">
            <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-4 border-b-4 border-ust-gold">
                <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                    <i class="fas fa-file-signature"></i>Organizer Applications
                </h3>
            </div>

            <div class="management-table-toolbar">
                <label for="applicationTableSearch" class="sr-only">Search organizer applications</label>
                <div class="management-table-search">
                    <i class="fas fa-search" aria-hidden="true"></i>
                    <input
                        id="applicationTableSearch"
                        type="search"
                        placeholder="Search applications"
                        class="input-field"
                    >
                </div>
                <p id="applicationTableSummary" class="management-table-summary"></p>
            </div>

            <div class="management-table-wrap">
                <table id="organizerApplicationsTable" class="management-table management-table--applications" data-page-size="10">
                    <thead class="bg-ust-light-bg border-b-2 border-ust-gold">
                        <tr>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Year Level</th>
                            <th>Reason</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody data-table-body>
                        <?php if (!empty($pendingApplications)) : ?>
                            <?php foreach($pendingApplications as $application) : ?>
                                <tr class="border-b hover:bg-ust-cream/50 transition" data-table-row>
                                    <td class="text-ust-dark font-semibold">
                                        <?= htmlspecialchars($application['first_name'] . ' ' . $application['last_name']) ?>
                                    </td>
                                    <td class="management-table__email text-ust-gray">
                                        <?= htmlspecialchars($application['email']) ?>
                                    </td>
                                    <td>
                                        <span class="management-table__badge bg-ust-gold/10 text-ust-dark">
                                            <?= htmlspecialchars($application['year_lvl_name']) ?>
                                        </span>
                                    </td>
                                    <td class="management-table__reason text-ust-gray">
                                        <div class="whitespace-pre-line">
                                            <?= nl2br(htmlspecialchars($application['reason'])) ?>
                                        </div>
                                    </td>
                                    <td class="management-table__date text-ust-gray">
                                        <?= date('M j, Y', strtotime($application['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="management-table__actions">
                                            <button
                                                type="button"
                                                class="application-action-btn management-icon-btn bg-emerald-600 hover:bg-emerald-700"
                                                data-action="approve"
                                                data-applicationid="<?= $application['organizer_application_id'] ?>"
                                                aria-label="Approve application <?= $application['organizer_application_id'] ?>"
                                                title="Approve application"
                                            >
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="application-action-btn management-icon-btn bg-red-500 hover:bg-red-600"
                                                data-action="reject"
                                                data-applicationid="<?= $application['organizer_application_id'] ?>"
                                                aria-label="Reject application <?= $application['organizer_application_id'] ?>"
                                                title="Reject application"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="p-6 text-center text-ust-gray">
                                    <i class="fas fa-inbox text-3xl mb-2 block opacity-50"></i>
                                    No pending organizer applications right now.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="applicationTablePagination" class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                <p data-pagination-status class="text-sm text-ust-gray"></p>
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        data-pagination-prev
                        class="rounded-lg border-2 border-ust-gold px-4 py-2 text-sm font-semibold text-ust-gold transition hover:bg-ust-gold/5 disabled:cursor-not-allowed disabled:border-gray-200 disabled:text-gray-400 disabled:hover:bg-transparent"
                    >
                        Previous
                    </button>
                    <span data-pagination-pages class="min-w-[5rem] text-center text-sm font-semibold text-ust-dark"></span>
                    <button
                        type="button"
                        data-pagination-next
                        class="rounded-lg border-2 border-ust-gold px-4 py-2 text-sm font-semibold text-ust-gold transition hover:bg-ust-gold/5 disabled:cursor-not-allowed disabled:border-gray-200 disabled:text-gray-400 disabled:hover:bg-transparent"
                    >
                        Next
                    </button>
                </div>
            </div>
        </section>
    </div>
        </main>
    </div>

    <!-- Chart Modal -->
    <div id="chartModal" class="hidden fixed inset-0 bg-black bg-opacity-60 z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-6xl max-h-[92vh] overflow-y-auto rounded-[1.75rem] bg-ust-light-bg shadow-ust-md border border-white/70">
            <div class="sticky top-0 z-10 overflow-hidden rounded-t-[1.75rem] border-b-4 border-ust-gold bg-ust-dark px-6 py-5 text-white">
                <div class="absolute inset-0 opacity-20" aria-hidden="true">
                    <div class="absolute -right-16 -top-20 h-44 w-44 rounded-full bg-ust-gold blur-3xl"></div>
                    <div class="absolute left-1/3 top-8 h-24 w-24 rounded-full bg-white blur-3xl"></div>
                </div>
                <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.26em] text-ust-gold">Analytics</p>
                        <h2 class="mt-2 text-2xl font-heading font-bold flex items-center gap-3">
                            <i class="fas fa-chart-pie"></i>User Management Insights
                        </h2>
                        <p class="mt-2 max-w-2xl text-sm text-white/75">
                            Role mix, year level distribution, account growth, update activity, and organizer application demand.
                        </p>
                    </div>
                    <button
                        onclick="closeChartModal()"
                        type="button"
                        class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:border-ust-gold hover:text-ust-gold"
                        aria-label="Close user charts"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <div class="p-5 md:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Total Users</p>
                        <p class="mt-3 text-3xl font-heading font-bold text-ust-dark" data-analytics-total-users>0</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">New This Month</p>
                        <p class="mt-3 text-3xl font-heading font-bold text-ust-gold" data-analytics-new-users>0</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Updated This Week</p>
                        <p class="mt-3 text-3xl font-heading font-bold text-ust-dark" data-analytics-updated-users>0</p>
                    </div>
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Pending Applications</p>
                        <p class="mt-3 text-3xl font-heading font-bold text-ust-dark" data-analytics-pending-applications>0</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-[0.9fr_1.1fr]">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Access Mix</p>
                                <h3 class="mt-1 font-heading text-lg font-bold text-ust-dark">Users by Role</h3>
                            </div>
                            <span class="rounded-full bg-ust-gold/10 px-3 py-1 text-xs font-semibold text-ust-dark">Role</span>
                        </div>
                        <div class="h-72">
                            <canvas id="roleChart"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Enrollment Spread</p>
                                <h3 class="mt-1 font-heading text-lg font-bold text-ust-dark">Users by Year Level</h3>
                            </div>
                            <span class="rounded-full bg-ust-gold/10 px-3 py-1 text-xs font-semibold text-ust-dark">Students</span>
                        </div>
                        <div class="h-72">
                            <canvas id="yearLevelChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-5 xl:grid-cols-2">
                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <div class="mb-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Momentum</p>
                            <h3 class="mt-1 font-heading text-lg font-bold text-ust-dark">New Users Over 6 Months</h3>
                        </div>
                        <div class="h-72">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                        <div class="mb-4">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Maintenance</p>
                            <h3 class="mt-1 font-heading text-lg font-bold text-ust-dark">User Update Freshness</h3>
                        </div>
                        <div class="h-72">
                            <canvas id="userUpdatesChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-gray-100 bg-white p-5 shadow-ust">
                    <div class="mb-4 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-ust-gray">Organizer Pipeline</p>
                            <h3 class="mt-1 font-heading text-lg font-bold text-ust-dark">Pending Applications by Year Level</h3>
                        </div>
                        <p class="text-sm text-ust-gray">Shows where organizer interest is currently coming from.</p>
                    </div>
                    <div class="h-80">
                        <canvas id="pendingApplicationsChart"></canvas>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        onclick="closeChartModal()"
                        class="rounded-lg border-2 border-ust-gold px-6 py-3 text-sm font-semibold text-ust-gold transition duration-200 hover:bg-ust-gold/5"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-8">

    <!-- Edit User Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-ust-md max-w-2xl w-full max-h-[90vh] overflow-y-auto border-t-4 border-ust-gold">
            <div class="sticky top-0 bg-gradient-to-r from-ust-dark to-ust-gray border-b-4 border-ust-gold px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-heading font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user-edit"></i>Edit User
                </h2>
                <button 
                    onclick="closeEditModal()"
                    type="button"
                    class="text-white hover:text-ust-gold transition"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="edit_fname" class="block text-sm font-semibold text-ust-dark mb-2">
                            First Name
                        </label>
                        <input
                            type="text"
                            id="edit_fname"
                            name="edit_fname"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div>
                        <label for="edit_lname" class="block text-sm font-semibold text-ust-dark mb-2">
                            Surname
                        </label>
                        <input
                            type="text"
                            id="edit_lname"
                            name="edit_lname"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="edit_email" class="block text-sm font-semibold text-ust-dark mb-2">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="edit_email"
                            name="edit_email"
                            class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="edit_password" class="block text-sm font-semibold text-ust-dark mb-2">
                                    Password (optional)
                                </label>
                                <input
                                    type="password"
                                    id="edit_password"
                                    name="edit_password"
                                    placeholder="Leave blank to keep current"
                                    class="input-field w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                                >
                            </div>
                            <div>
                                <label for="edit_yearlvl" class="block text-sm font-semibold text-ust-dark mb-2">
                                    Year Level
                                </label>
                                <select
                                    id="edit_yearlvl"
                                    name="edit_yearlvl"
                                    class="input-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm font-body text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition"
                                >
                                    <option value="">Choose Year Level</option>
                                    <?php foreach($yearlvl as $year): ?>
                                        <option value="<?= $year['year_lvl_id'] ?>">
                                            <?= $year['year_lvl_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label for="edit_role" class="block text-sm font-semibold text-ust-dark mb-2">
                                    Role
                                </label>
                                <select
                                    id="edit_role"
                                    name="edit_role"
                                    class="input-field w-full rounded-lg border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm font-body text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition"
                                >
                                    <option value="">Choose Role</option>
                                    <?php foreach($roles as $role): ?>
                                        <option value="<?= $role['role_id'] ?>">
                                            <?= $role['role_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="px-6 py-3 rounded-lg border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 text-sm font-semibold transition duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        id="updateSubmitBtn"
                        type="button"
                        class="px-6 py-3 rounded-lg text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark shadow-ust transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(userID) {
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').dataset.userID = userID;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').dataset.userID = '';
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Chart Modal Functions
        function openChartModal() {
            document.getElementById('chartModal').classList.remove('hidden');
            setTimeout(initializeCharts, 300);
        }

        function closeChartModal() {
            document.getElementById('chartModal').classList.add('hidden');
            destroyCharts();
        }

        document.getElementById('chartModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeChartModal();
            }
        });

        const userAnalyticsData = <?= json_encode($userAnalyticsData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
        const chartPalette = ['#F4C300', '#1A1A1A', '#D4A400', '#FED766', '#333333', '#B88900', '#F8E08E'];
        let roleChartInstance = null;
        let yearLevelChartInstance = null;
        let userGrowthChartInstance = null;
        let userUpdatesChartInstance = null;
        let pendingApplicationsChartInstance = null;

        function updateAnalyticsSummary() {
            document.querySelector('[data-analytics-total-users]').textContent = userAnalyticsData.summary.totalUsers;
            document.querySelector('[data-analytics-new-users]').textContent = userAnalyticsData.summary.newUsersThisMonth;
            document.querySelector('[data-analytics-updated-users]').textContent = userAnalyticsData.summary.updatedThisWeek;
            document.querySelector('[data-analytics-pending-applications]').textContent = userAnalyticsData.summary.pendingApplications;
        }

        function chartBaseOptions(extraOptions = {}) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            color: '#1a1a1a',
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 12,
                                weight: '600'
                            },
                            padding: 16
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1a1a1a',
                        titleColor: '#F4C300',
                        bodyColor: '#ffffff',
                        borderColor: '#F4C300',
                        borderWidth: 1,
                        titleFont: {
                            family: "'Outfit', sans-serif",
                            size: 14,
                            weight: '700'
                        },
                        bodyFont: {
                            family: "'Inter', sans-serif",
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                ...extraOptions
            };
        }

        function axisOptions(indexAxis = 'x') {
            const valueAxis = indexAxis === 'y' ? 'x' : 'y';
            const categoryAxis = indexAxis === 'y' ? 'y' : 'x';

            return {
                indexAxis,
                scales: {
                    [valueAxis]: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            color: '#6b7280',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(26, 26, 26, 0.08)'
                        }
                    },
                    [categoryAxis]: {
                        ticks: {
                            color: '#1a1a1a',
                            font: {
                                family: "'Outfit', sans-serif",
                                size: 12,
                                weight: '700'
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            };
        }

        function initializeCharts() {
            destroyCharts();
            updateAnalyticsSummary();

            Chart.defaults.font.family = "'Inter', sans-serif";
            Chart.defaults.color = '#333333';

            const roleLabels = Object.keys(userAnalyticsData.roles);
            const roleValues = Object.values(userAnalyticsData.roles);
            const roleCtx = document.getElementById('roleChart').getContext('2d');
            roleChartInstance = new Chart(roleCtx, {
                type: 'doughnut',
                data: {
                    labels: roleLabels,
                    datasets: [{
                        data: roleValues,
                        backgroundColor: chartPalette.slice(0, roleLabels.length),
                        borderColor: '#ffffff',
                        borderWidth: 4,
                        hoverOffset: 12
                    }]
                },
                options: chartBaseOptions({
                    cutout: '62%'
                })
            });

            const yearLevelLabels = Object.keys(userAnalyticsData.yearLevels);
            const yearLevelValues = Object.values(userAnalyticsData.yearLevels);
            const yearCtx = document.getElementById('yearLevelChart').getContext('2d');
            yearLevelChartInstance = new Chart(yearCtx, {
                type: 'bar',
                data: {
                    labels: yearLevelLabels,
                    datasets: [{
                        label: 'Users',
                        data: yearLevelValues,
                        backgroundColor: '#F4C300',
                        borderColor: '#D4A400',
                        borderWidth: 2,
                        borderRadius: 10,
                        maxBarThickness: 42
                    }]
                },
                options: chartBaseOptions({
                    plugins: {
                        ...chartBaseOptions().plugins,
                        legend: {
                            display: false
                        }
                    },
                    ...axisOptions('y')
                })
            });

            const growthCtx = document.getElementById('userGrowthChart').getContext('2d');
            const growthGradient = growthCtx.createLinearGradient(0, 0, 0, 260);
            growthGradient.addColorStop(0, 'rgba(244, 195, 0, 0.35)');
            growthGradient.addColorStop(1, 'rgba(244, 195, 0, 0.03)');
            userGrowthChartInstance = new Chart(growthCtx, {
                type: 'line',
                data: {
                    labels: userAnalyticsData.growth.map((month) => month.label),
                    datasets: [{
                        label: 'New users',
                        data: userAnalyticsData.growth.map((month) => month.count),
                        fill: true,
                        backgroundColor: growthGradient,
                        borderColor: '#D4A400',
                        borderWidth: 3,
                        pointBackgroundColor: '#1A1A1A',
                        pointBorderColor: '#F4C300',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        tension: 0.38
                    }]
                },
                options: chartBaseOptions({
                    plugins: {
                        ...chartBaseOptions().plugins,
                        legend: {
                            display: false
                        }
                    },
                    scales: axisOptions('x').scales
                })
            });

            const updateLabels = Object.keys(userAnalyticsData.updates);
            const updateValues = Object.values(userAnalyticsData.updates);
            const updatesCtx = document.getElementById('userUpdatesChart').getContext('2d');
            userUpdatesChartInstance = new Chart(updatesCtx, {
                type: 'polarArea',
                data: {
                    labels: updateLabels,
                    datasets: [{
                        data: updateValues,
                        backgroundColor: ['rgba(244, 195, 0, 0.82)', 'rgba(26, 26, 26, 0.82)', 'rgba(212, 164, 0, 0.72)'],
                        borderColor: '#ffffff',
                        borderWidth: 3
                    }]
                },
                options: chartBaseOptions({
                    scales: {
                        r: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                backdropColor: 'transparent',
                                color: '#6b7280'
                            },
                            grid: {
                                color: 'rgba(26, 26, 26, 0.08)'
                            },
                            angleLines: {
                                color: 'rgba(26, 26, 26, 0.08)'
                            }
                        }
                    }
                })
            });

            const applicationLabels = Object.keys(userAnalyticsData.pendingApplications);
            const applicationValues = Object.values(userAnalyticsData.pendingApplications);
            const applicationsCtx = document.getElementById('pendingApplicationsChart').getContext('2d');
            pendingApplicationsChartInstance = new Chart(applicationsCtx, {
                type: 'bar',
                data: {
                    labels: applicationLabels,
                    datasets: [{
                        label: 'Pending applications',
                        data: applicationValues,
                        backgroundColor: chartPalette.map((color, index) => index % 2 === 0 ? color : '#1A1A1A'),
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        borderRadius: 10,
                        maxBarThickness: 48
                    }]
                },
                options: chartBaseOptions({
                    plugins: {
                        ...chartBaseOptions().plugins,
                        legend: {
                            display: false
                        }
                    },
                    scales: axisOptions('x').scales
                })
            });
        }

        function destroyCharts() {
            if (roleChartInstance) {
                roleChartInstance.destroy();
                roleChartInstance = null;
            }
            if (yearLevelChartInstance) {
                yearLevelChartInstance.destroy();
                yearLevelChartInstance = null;
            }
            if (userGrowthChartInstance) {
                userGrowthChartInstance.destroy();
                userGrowthChartInstance = null;
            }
            if (userUpdatesChartInstance) {
                userUpdatesChartInstance.destroy();
                userUpdatesChartInstance = null;
            }
            if (pendingApplicationsChartInstance) {
                pendingApplicationsChartInstance.destroy();
                pendingApplicationsChartInstance = null;
            }
        }
    </script>

    <?php require __DIR__ . '/partials/footer.php'; ?>

    <script src="../script/utils.js"></script>
    <script src="../script/admin.js"></script>
    <script src="../script/management-nav.js"></script>
</body>
</html>
