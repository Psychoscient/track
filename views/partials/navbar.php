<?php
    $activePage = $activePage ?? '';
    $showDashboardLink = $showDashboardLink ?? false;

    $navItems = [
        ['key' => 'home', 'label' => 'Home', 'href' => 'home.php'],
        ['key' => 'events', 'label' => 'Events', 'href' => 'events.php'],
        ['key' => 'about', 'label' => 'About', 'href' => '#'],
        ['key' => 'contact', 'label' => 'Contact', 'href' => '#'],
    ];

    if ($showDashboardLink) {
        $navItems[] = ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php'];
    }
?>

<header class="bg-white shadow-ust border-b-4 border-ust-gold">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
        <?php $logoVariant = 'navbar'; require __DIR__ . '/logo.php'; ?>

        <nav class="hidden md:flex items-center gap-6">
            <?php foreach ($navItems as $item) : ?>
                <a
                    href="<?= $item['href'] ?>"
                    class="<?= $activePage === $item['key']
                        ? 'text-ust-gold font-semibold'
                        : 'text-ust-dark hover:text-ust-gold font-medium'; ?> transition"
                >
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>

            <button id="logout" class="text-white rounded-lg bg-ust-gold hover:bg-ust-gold-dark px-4 py-2 font-semibold transition">
                <i class="fas fa-sign-out-alt mr-2"></i>Logout
            </button>
        </nav>
    </div>
</header>
