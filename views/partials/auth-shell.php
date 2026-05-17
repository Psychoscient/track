<?php
    $authEyebrow = $authEyebrow ?? 'Thomasian Access';
    $authTitle = $authTitle ?? 'Access the platform';
    $authSubtitle = $authSubtitle ?? 'Continue with your TRACK account.';
    $authPhoto = $authPhoto ?? '../public/images/auth/ust-campus-main.jpeg';
    $authPhotoPosition = $authPhotoPosition ?? 'center center';
    $authFormTemplate = $authFormTemplate ?? null;
?>

<div class="relative grow overflow-hidden">
    <div
        class="absolute inset-0 bg-cover bg-center"
        style="background-image: url('<?= htmlspecialchars($authPhoto, ENT_QUOTES) ?>'); background-position: <?= htmlspecialchars($authPhotoPosition, ENT_QUOTES) ?>;"
    ></div>
    <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(12,12,12,.84),rgba(12,12,12,.62)_46%,rgba(92,62,0,.38)),radial-gradient(circle_at_top,rgba(244,195,0,.18),transparent_38%),radial-gradient(circle_at_80%_20%,rgba(255,255,255,.10),transparent_28%)]"></div>
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_18%,rgba(255,255,255,.12),transparent_26%),radial-gradient(circle_at_18%_78%,rgba(244,195,0,.10),transparent_22%),radial-gradient(circle_at_82%_82%,rgba(255,255,255,.08),transparent_18%)]"></div>

    <div class="relative mx-auto flex min-h-full max-w-7xl items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="relative w-full max-w-[34rem] overflow-hidden rounded-[2rem] border border-white/20 bg-white/14 p-6 shadow-[0_24px_80px_rgba(0,0,0,.42)] backdrop-blur-2xl sm:p-8 lg:p-10">
            <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(180deg,rgba(255,255,255,.16),rgba(255,255,255,.04)_26%,rgba(255,255,255,0)_62%)]"></div>
            <div class="relative flex flex-col items-center text-center">
                <?php $logoVariant = 'auth'; require __DIR__ . '/logo.php'; ?>

                <p class="mt-6 text-[0.7rem] font-semibold uppercase tracking-[0.38em] text-ust-gold/95">
                    <?= htmlspecialchars($authEyebrow) ?>
                </p>
                <h2 class="mt-3 max-w-xl text-3xl font-heading font-bold leading-tight text-white sm:text-4xl">
                    <?= htmlspecialchars($authTitle) ?>
                </h2>
                <p class="mt-4 max-w-lg text-sm leading-7 text-white/78">
                    <?= htmlspecialchars($authSubtitle) ?>
                </p>
            </div>

            <div class="relative mt-8">
                <?php require $authFormTemplate; ?>
            </div>
        </div>
    </div>
</div>
