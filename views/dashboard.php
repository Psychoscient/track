<?php
    session_start();
    require_once "../bl/UserManager.php";
    require_once "../bl/YearLevelManager.php";
    require_once "../bl/RoleManager.php";

    $users = new UserManager();
    $usersDetails = $users -> getUsersWithRelations();
    // echo "<pre>";
    // print_r($usersDetails);
    // echo "</pre>";
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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="../dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100 p-6">

    <!-- Summary Cards -->
    <style>
        .summary-card {
            position: relative;
            border-bottom: 3px solid transparent;
            transition: border-color 0.25s cubic-bezier(.4,0,.2,1), box-shadow 0.2s;
        }
        .summary-card:hover {
            border-bottom: 3px solid #6366f1; /* subtle indigo */
            box-shadow: 0 2px 8px 0 rgba(99,102,241,0.06);
        }
    </style>
    <div class="max-w-6xl mx-auto mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="summary-card bg-white rounded-2xl shadow-md p-6 flex flex-col items-center justify-center cursor-pointer">
                <div class="text-4xl font-extrabold text-indigo-600 mb-2">
                    <?= $totalUsers['total_users']; ?>
                </div>
                <div class="text-sm text-gray-500 font-medium">Total Users</div>
            </div>
            <!-- Card 2 -->
            <div class="summary-card bg-white rounded-2xl shadow-md p-6 flex flex-col items-center justify-center cursor-pointer">
                <div class="text-4xl font-extrabold text-green-600 mb-2">0</div>
                <div class="text-sm text-gray-500 font-medium">Active Users</div>
            </div>
            <!-- Card 3 -->
            <div class="summary-card bg-white rounded-2xl shadow-md p-6 flex flex-col items-center justify-center cursor-pointer">
                <div class="text-4xl font-extrabold text-blue-600 mb-2">0</div>
                <div class="text-sm text-gray-500 font-medium">Admins</div>
            </div>
            <!-- Card 4 -->
            <div class="summary-card bg-white rounded-2xl shadow-md p-6 flex flex-col items-center justify-center cursor-pointer">
                <div class="text-4xl font-extrabold text-red-600 mb-2">0</div>
                <div class="text-sm text-gray-500 font-medium">Pending Approvals</div>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">User Management</h1>
        </div>

        <!-- Create User Panel -->
        <div class="bg-white border border-gray-200 shadow-sm rounded-2xl mb-6 overflow-hidden">
            <div id="updateForm" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="fname" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name
                        </label>
                        <input
                            type="text"
                            id="fname"
                            name="fname"
                            required
                            placeholder="Enter first name"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div>
                        <label for="lname" class="block text-sm font-medium text-gray-700 mb-1">
                            Surname
                        </label>
                        <input
                            type="text"
                            id="lname"
                            name="lname"
                            required
                            placeholder="Enter surname"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            placeholder="Enter email address"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <!-- Group password, year level, and role in a single row -->
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="Enter password"
                                    class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                >
                            </div>
                            <div>
                                <label for="yearlvl" class="block text-sm font-medium text-gray-700 mb-1">
                                    Year Level
                                </label>
                                <select
                                    id="yearlvl"
                                    name="yearlvl"
                                    required
                                    class="input-field w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
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
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role
                                </label>
                                <select
                                    id="role"
                                    name="role"
                                    required
                                    class="input-field w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
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
                        class="px-6 py-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create User
                    </button>
                    <button
                        type="button"
                        onclick="Utils.resetFields()"
                        class="px-6 py-3 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 active:bg-gray-100 transition duration-200"
                    >
                        Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-xl overflow-hidden">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="p-4 font-medium">User ID</th>
                        <th class="p-4 font-medium">First Name</th>
                        <th class="p-4 font-medium">Last Name</th>
                        <th class="p-4 font-medium">Email</th>
                        <th class="p-4 font-medium">Year Level</th>
                        <th class="p-4 font-medium">Role</th>
                        <th class="p-4 font-medium">Created At</th>
                        <th class="p-4 font-medium">Updated At</th>
                        <th class="p-4 font-medium">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($usersDetails)) : ?>
                        <?php foreach($usersDetails as $index => $user) : ?>
                            <tr class="border-b">
                                <td class="p-4 font-medium"><?= $user['user_id'] ?></td>
                                <td class="p-4"><?= $user['first_name'] ?></td>
                                <td class="p-4"><?= $user['last_name'] ?></td>
                                <td class="p-4"><?= $user['email'] ?></td>
                                <td class="p-4" data-year-level-id="<?= $user['year_lvl_id'] ?? '' ?>"><?= $user['year_lvl_name'] ?></td>
                                <td class="p-4" data-role-id="<?= $user['role_id'] ?? '' ?>"><?= $user['role_name'] ?></td>
                                <td class="p-4"><?= $user['user_created_at'] ?></td>
                                <td class="p-4"><?= $user['user_updated_at'] ?></td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            id="editBtn" 
                                            class="dashboard-btn flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition"
                                            data-action="edit"
                                            data-userid="<?= $user['user_id'] ?>"
                                        >
                                            Edit
                                        </button>

                                        <button 
                                            id="deleteBtn"
                                            class="dashboard-btn flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition"
                                            data-action="delete"
                                            data-userid="<?= $user['user_id'] ?>"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <td class="p-4">No data found.</td>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Edit User</h2>
                <button 
                    onclick="closeEditModal()"
                    type="button"
                    class="text-gray-400 hover:text-gray-600 transition"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="edit_fname" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name
                        </label>
                        <input
                            type="text"
                            id="edit_fname"
                            name="fname"
                            required
                            placeholder="Enter first name"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div>
                        <label for="edit_lname" class="block text-sm font-medium text-gray-700 mb-1">
                            Surname
                        </label>
                        <input
                            type="text"
                            id="edit_lname"
                            name="lname"
                            required
                            placeholder="Enter surname"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="edit_email"
                            name="email"
                            required
                            placeholder="Enter email address"
                            class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label for="edit_password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password
                                </label>
                                <input
                                    type="password"
                                    id="edit_password"
                                    name="password"
                                    placeholder="Leave empty to keep current password"
                                    class="input-field w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
                                >
                            </div>
                            <div>
                                <label for="edit_yearlvl" class="block text-sm font-medium text-gray-700 mb-1">
                                    Year Level
                                </label>
                                <select
                                    id="edit_yearlvl"
                                    name="yearlvl"
                                    required
                                    class="input-field w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
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
                                <label for="edit_role" class="block text-sm font-medium text-gray-700 mb-1">
                                    Role
                                </label>
                                <select
                                    id="edit_role"
                                    name="role"
                                    required
                                    class="input-field w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200"
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
                        type="button"
                        onclick="closeEditModal()"
                        class="px-6 py-3 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 active:bg-gray-100 transition duration-200"
                    >
                        Cancel
                    </button>
                    <button
                        id="updateSubmitBtn"
                        type="button"
                        class="px-6 py-3 rounded-xl text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-md hover:shadow-lg transition duration-200 flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
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

        // Close modal when clicking outside
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