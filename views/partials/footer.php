<?php
    $showDashboardLink = $showDashboardLink ?? false;
?>

<footer class="relative z-10 mt-auto border-t-4 border-ust-gold bg-ust-dark text-white">
    <div class="max-w-7xl mx-auto px-6 py-8 lg:py-9">
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-[1.2fr_0.65fr_0.9fr_0.75fr] lg:gap-10 lg:items-start">
            <div class="max-w-xl">
                <div class="mb-4">
                    <?php $logoVariant = 'footer'; require __DIR__ . '/logo.php'; ?>
                </div>
                <p class="max-w-lg text-sm leading-7 text-gray-300">
                    TRACK supports the UST community through a clear, shared view of campus programs, schedules, and event activity.
                </p>
            </div>

            <div>
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.24em] text-ust-gold">Platform</p>
                <nav aria-label="Footer navigation" class="flex flex-col items-start gap-3 text-sm font-semibold text-gray-300">
                    <a href="home.php" class="hover:text-ust-gold transition">Home</a>
                    <a href="events.php" class="hover:text-ust-gold transition">Events</a>
                    <?php if ($showDashboardLink) : ?>
                        <a href="dashboard.php" class="hover:text-ust-gold transition">Dashboard</a>
                    <?php endif; ?>
                </nav>
            </div>

            <div>
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.24em] text-ust-gold">University Focus</p>
                <p class="text-sm leading-7 text-gray-300">
                    Built for coordinated event tracking across UST, from student activities to institution-wide programs.
                </p>
            </div>

            <div>
                <p class="mb-4 text-xs font-semibold uppercase tracking-[0.24em] text-ust-gold">Details</p>
                <div class="space-y-3 text-sm text-gray-400">
                    <p>&copy; <?php echo date("Y"); ?> TRACK</p>
                    <p class="text-xs uppercase tracking-[0.22em] text-gray-500">UST Events Tracker</p>
                </div>
            </div>
        </div>
    </div>
</footer>
