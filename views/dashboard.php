<?php
    session_start();
    require_once "../bl/UserManager.php";
    require_once "../bl/YearLevelManager.php";

    $users = new UserManager();
    $usersDetails = $users -> getUsersWithRelations();

    $yearlvlmanager = new YearLevelManager();
    $yearlvl = $yearlvlmanager -> getYearLevel();

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
    <link href="./dist/output.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Admin Dashboard</title>
</head>
<body class="bg-gray-100 p-6">

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
            </div>

            <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">
                <button
                    onclick="Utils.resetFields()"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition"
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
                                <td class="p-4"><?= $user['year_lvl_name'] ?></td>
                                <td class="p-4"><?= $user['role_name'] ?></td>
                                <td class="p-4"><?= $user['user_created_at'] ?></td>
                                <td class="p-4"><?= $user['user_updated_at'] ?></td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <button
                                            id="updateBtn" 
                                            class="dashboard-btn flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition"
                                            data-action="update"
                                            data-userid="<?= $user['user_id'] ?>"
                                        >
                                            Update
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

    <script src="../script/utils.js"></script>
    <script src="../script/admin.js"></script>
</body>
</html>