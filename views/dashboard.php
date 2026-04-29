<?php
    session_start();
    require_once "../bl/UserManager.php";
    require_once "../bl/YearLevelManager.php";
    require_once "../bl/RoleManager.php";

    $users = new UserManager();
    $usersDetails = $users -> getUsersWithRelations();

    $totalUsers = $users -> getTotalUsers();

    $yearlvlmanager = new YearLevelManager();
    $yearlvl = $yearlvlmanager -> getYearLevel();

    $rolemanager = new RoleManager();
    $roles = $rolemanager -> getRoles();

    if (!isset($_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

    if (!in_array('manage_users', $_SESSION['permissions'])) {
        header("Location: unauthorized.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../public/output.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Admin Dashboard - School Events Tracker</title>
</head>
<body class="font-body bg-ust-light-bg">

    <!-- Header -->
    <header class="bg-white shadow-ust border-b-4 border-ust-gold">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-ust-gold rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-ust-dark text-lg"></i>
                </div>
                <h1 class="text-2xl font-heading font-bold text-ust-dark">Admin Dashboard</h1>
            </div>
            <button id="logout" class="text-white rounded-lg bg-ust-gold hover:bg-ust-gold-dark px-4 py-2 font-semibold transition">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </div>
    </header>

    <!-- Summary Cards -->
    <style>
        .summary-card {
            position: relative;
            border-top: 4px solid transparent;
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
        }
        .summary-card:hover {
            border-top: 4px solid #F4C300;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 26, 26, 0.12);
        }
    </style>
    
    <div class="max-w-7xl mx-auto px-6 py-8 mb-8">
        <h2 class="text-2xl font-heading font-bold text-ust-dark mb-6">Dashboard Overview</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Card 1 - Total Users -->
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center cursor-pointer border border-gray-100">
                <div class="text-4xl font-heading font-bold text-ust-gold mb-2">
                    <?= $totalUsers['total_users']; ?>
                </div>
                <div class="text-sm text-ust-gray font-semibold flex items-center gap-2">
                    <i class="fas fa-users"></i>Total Users
                </div>
            </div>

            <!-- Card 2 - Admins -->
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center cursor-pointer border border-gray-100">
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
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center cursor-pointer border border-gray-100">
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
            <div class="summary-card bg-white rounded-lg shadow-ust p-6 flex flex-col items-center justify-center cursor-pointer border border-gray-100">
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
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 pb-8">
        <!-- Create User Panel -->
        <div class="bg-white border border-gray-200 shadow-ust rounded-lg mb-8 overflow-hidden">
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
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow-ust rounded-lg overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-ust-dark to-ust-gray px-6 py-4 border-b-4 border-ust-gold">
                <h3 class="text-lg font-heading font-bold text-white flex items-center gap-2">
                    <i class="fas fa-list"></i>User Management
                </h3>
            </div>
            <table class="min-w-full text-sm text-left">
                <thead class="bg-ust-light-bg border-b-2 border-ust-gold">
                    <tr>
                        <th class="p-4 font-semibold text-ust-dark"><i class="fas fa-hashtag mr-2"></i>User ID</th>
                        <th class="p-4 font-semibold text-ust-dark">First Name</th>
                        <th class="p-4 font-semibold text-ust-dark">Last Name</th>
                        <th class="p-4 font-semibold text-ust-dark">Email</th>
                        <th class="p-4 font-semibold text-ust-dark">Year Level</th>
                        <th class="p-4 font-semibold text-ust-dark">Role</th>
                        <th class="p-4 font-semibold text-ust-dark">Created</th>
                        <th class="p-4 font-semibold text-ust-dark">Updated</th>
                        <th class="p-4 font-semibold text-ust-dark">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php  
                        usort($usersDetails, function($a, $b) {
                            return $a['user_id'] <=> $b['user_id'];
                        });
                    ?>
                    <?php if (!empty($usersDetails)) : ?>
                        <?php foreach($usersDetails as $index => $user) : ?>
                            <tr class="border-b hover:bg-ust-cream/50 transition">
                                <td class="p-4 font-semibold text-ust-gold"><?= $user['user_id'] ?></td>
                                <td class="p-4 text-ust-dark"><?= $user['first_name'] ?></td>
                                <td class="p-4 text-ust-dark"><?= $user['last_name'] ?></td>
                                <td class="p-4 text-ust-gray text-xs"><?= $user['email'] ?></td>
                                <td class="p-4" data-year-level-id="<?= $user['year_lvl_id'] ?? '' ?>">
                                    <span class="inline-block px-3 py-1 bg-ust-gold/10 text-ust-dark text-xs font-semibold rounded-full">
                                        <?= $user['year_lvl_name'] ?>
                                    </span>
                                </td>
                                <td class="p-4" data-role-id="<?= $user['role_id'] ?? '' ?>">
                                    <span class="inline-block px-3 py-1 bg-ust-gold/20 text-ust-dark text-xs font-semibold rounded-full">
                                        <?= $user['role_name'] ?>
                                    </span>
                                </td>
                                <td class="p-4 text-ust-gray text-xs"><?= $user['user_created_at'] ?></td>
                                <td class="p-4 text-ust-gray text-xs"><?= $user['user_updated_at'] ?></td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            id="editBtn" 
                                            class="dashboard-btn flex items-center gap-1 px-3 py-1.5 text-sm font-semibold text-white bg-ust-gold hover:bg-ust-gold-dark rounded-lg transition"
                                            data-action="edit"
                                            data-userid="<?= $user['user_id'] ?>"
                                        >
                                            <i class="fas fa-edit"></i>Edit
                                        </button>

                                        <button 
                                            id="deleteBtn"
                                            class="dashboard-btn flex items-center gap-1 px-3 py-1.5 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-lg transition"
                                            data-action="delete"
                                            data-userid="<?= $user['user_id'] ?>"
                                        >
                                            <i class="fas fa-trash"></i>Delete
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
    </div>

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
    </script>

    <script src="../script/utils.js"></script>
    <script src="../script/admin.js"></script>
</body>
</html>
