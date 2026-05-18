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

<body class="min-h-screen font-body text-ust-dark bg-ust-cream">
    <main class="signup-page">
        <section class="signup-shell" aria-label="Create an account">
            <aside class="signup-hero" aria-label="TRACK overview">
                <span class="signup-hero__glow signup-hero__glow--one" aria-hidden="true"></span>
                <span class="signup-hero__glow signup-hero__glow--two" aria-hidden="true"></span>
                <span class="signup-hero__grid" aria-hidden="true"></span>

                <div class="signup-hero__content">
                    <div class="signup-hero__brand">
                        <?php $logoVariant = 'navbar'; require __DIR__ . '/partials/logo.php'; ?>
                    </div>

                    <div class="signup-hero__copy">
                        <p class="signup-hero__eyebrow">Campus event access</p>
                        <h1>Track campus events smarter.</h1>
                        <p>
                            Keep student activities, organizations, and updates in one clear place built for the UST community.
                        </p>
                    </div>

                    <ul class="signup-hero__features" aria-label="Platform benefits">
                        <li>
                            <span aria-hidden="true"><i class="fas fa-calendar-check"></i></span>
                            Discover events
                        </li>
                        <li>
                            <span aria-hidden="true"><i class="fas fa-users"></i></span>
                            Join organizations
                        </li>
                        <li>
                            <span aria-hidden="true"><i class="fas fa-bell"></i></span>
                            Stay updated
                        </li>
                    </ul>
                </div>

                <div class="signup-dashboard" aria-hidden="true">
                    <div class="signup-dashboard__topbar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <div class="signup-dashboard__summary">
                        <div>
                            <strong>12</strong>
                            <span>Upcoming</span>
                        </div>
                        <div>
                            <strong>8</strong>
                            <span>Organizations</span>
                        </div>
                        <div>
                            <strong>24</strong>
                            <span>Updates</span>
                        </div>
                    </div>
                    <div class="signup-dashboard__schedule">
                        <article>
                            <span></span>
                            <div>
                                <strong>Leadership Forum</strong>
                                <small>Today - 2:00 PM</small>
                            </div>
                        </article>
                        <article>
                            <span></span>
                            <div>
                                <strong>Org Fair</strong>
                                <small>Tomorrow - Main Quad</small>
                            </div>
                        </article>
                    </div>
                </div>
            </aside>

            <section class="signup-form-panel">
                <div class="signup-form-card">
                    <header class="signup-form-card__header">
                        <h2>Create an Account</h2>
                        <p>Sign up to access the system</p>
                    </header>

                    <?php require __DIR__ . '/partials/auth/signup-form.php'; ?>
                </div>
            </section>
        </section>
    </main>

    <?php $showDashboardLink = false; require __DIR__ . '/partials/footer.php'; ?>

    <script src="../script/utils.js?v=20260517"></script>
    <script src="../script/signup.js?v=20260517"></script>
</body>
</html>
