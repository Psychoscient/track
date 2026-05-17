<?php
    $managementArea = $managementArea ?? 'users';
    $managementSections = $managementSections ?? [];

    $managementAreas = [
        [
            'key' => 'users',
            'label' => 'User Management',
            'href' => 'dashboard.php',
            'icon' => 'fa-users'
        ],
        [
            'key' => 'events',
            'label' => 'Event Management',
            'href' => 'event-management.php',
            'icon' => 'fa-calendar-days'
        ],
    ];
?>

<button
    id="managementNavToggle"
    type="button"
    class="lg:hidden inline-flex items-center gap-2 rounded-lg border border-ust-gold/30 bg-white px-4 py-3 text-sm font-semibold text-ust-dark shadow-ust"
    aria-controls="managementNav"
    aria-expanded="false"
>
    <i class="fas fa-bars text-ust-gold"></i>
    Management Menu
</button>

<div id="managementNavBackdrop" class="hidden fixed inset-0 z-30 bg-black/40 lg:hidden"></div>

<aside
    id="managementNav"
    class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-black/5 bg-white shadow-ust-md transition duration-200 lg:sticky lg:top-6 lg:z-auto lg:h-fit lg:w-64 lg:translate-x-0 lg:rounded-2xl lg:border"
>
    <div class="flex h-full flex-col">
        <div class="border-b border-gray-100 px-5 py-5">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-ust-gold">Admin Workspace</p>
            <h2 class="mt-2 font-heading text-xl font-bold text-ust-dark">Management</h2>
        </div>

        <nav class="px-4 py-4">
            <p class="px-2 text-xs font-semibold uppercase tracking-[0.2em] text-ust-gray">Areas</p>
            <div class="mt-3 space-y-1">
                <?php foreach ($managementAreas as $area) : ?>
                    <a
                        href="<?= $area['href'] ?>"
                        class="<?= $managementArea === $area['key']
                            ? 'bg-ust-gold text-ust-dark'
                            : 'text-ust-gray hover:bg-ust-cream hover:text-ust-dark'; ?> flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-semibold transition"
                    >
                        <i class="fas <?= $area['icon'] ?> w-4 text-center"></i>
                        <?= $area['label'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>

        <nav class="border-t border-gray-100 px-4 py-4">
            <p class="px-2 text-xs font-semibold uppercase tracking-[0.2em] text-ust-gray">Sections</p>
            <div class="mt-3 space-y-1">
                <?php foreach ($managementSections as $section) : ?>
                    <a
                        href="#<?= $section['id'] ?>"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-ust-gray transition hover:bg-ust-cream hover:text-ust-dark"
                    >
                        <i class="fas <?= $section['icon'] ?> w-4 text-center text-ust-gold"></i>
                        <?= $section['label'] ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </nav>
    </div>
</aside>
