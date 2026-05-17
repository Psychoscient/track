<?php
    session_start();

    require_once "../bl/UserManager.php";

    $users = new UserManager();
    $usersDetails = $users -> getUsersWithRelations();

    if (!isset($_GET['token'])) {
        header("Location: unauthorized.php");
        exit;
    }

    if ($users -> isValidToken($_GET['token'])['status'] === false) {
        // echo "<script>alert('" . $users -> isValidToken($_GET['token'])['message'] . "');</script>";
        header("Location: unauthorized.php");
        exit;
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/output.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <title>Reset Password - TRACK</title>
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
                    <h2 class="text-2xl font-heading font-semibold text-ust-dark">Create New Password</h2>
                    <p class="text-sm text-ust-gray mt-2">Secure your account with a strong password</p>
                </div>

                <form id="resetForm" class="space-y-6">
                    <input type="hidden" name="action" value="reset-password">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? '', ENT_QUOTES); ?>">

                    <div>
                        <label for="newPassword" class="block text-sm font-semibold text-ust-dark mb-2">
                            New Password
                        </label>
                        <input 
                            type="password" 
                            id="newPassword" 
                            name="newPassword" 
                            required
                            placeholder="Create a strong password"
                            class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
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
                            class="w-full rounded-lg border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-lg shadow-ust transition duration-200 flex items-center justify-center gap-2"
                    >
                        <i class="fas fa-lock"></i>
                        Reset Password
                    </button>

                    <div class="text-center pt-2">
                        <a href="forgot-password.php" class="text-sm text-ust-gold hover:text-ust-gold-dark font-medium transition">
                            <i class="fas fa-redo mr-1"></i>
                            Request New Link
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php $showDashboardLink = false; require __DIR__ . '/partials/footer.php'; ?>

    <script src="../script/utils.js"></script>
    <script src="../script/reset-password.js"></script>
</body>
</html>
