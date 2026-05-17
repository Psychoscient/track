<?php
    $logoVariant = $logoVariant ?? 'navbar';

    switch ($logoVariant) {
        case 'auth':
            $wrapperClass = 'flex flex-col items-center text-center gap-4';
            $markClass = 'w-20 h-20 rounded-[1.75rem] bg-white/75 shadow-ust-md ring-1 ring-ust-gold/20 flex items-center justify-center';
            $svgClass = 'w-14 h-14';
            $titleClass = 'text-4xl font-heading font-bold text-white leading-none drop-shadow-[0_2px_12px_rgba(0,0,0,.35)]';
            $subtitleClass = 'text-sm uppercase tracking-[0.28em] text-ust-gold/90 font-semibold';
            break;
        case 'footer':
            $wrapperClass = 'flex items-center gap-3';
            $markClass = 'w-12 h-12 rounded-2xl bg-ust-gold shadow-ust flex items-center justify-center';
            $svgClass = 'w-8 h-8';
            $titleClass = 'font-heading font-bold text-white text-2xl leading-none';
            $subtitleClass = 'text-[11px] uppercase tracking-[0.24em] text-gray-300 font-semibold';
            break;
        case 'navbar':
        default:
            $wrapperClass = 'flex items-center gap-3';
            $markClass = 'w-12 h-12 rounded-2xl bg-ust-gold shadow-ust flex items-center justify-center';
            $svgClass = 'w-8 h-8';
            $titleClass = 'text-2xl font-heading font-bold text-ust-dark leading-tight';
            $subtitleClass = 'text-[11px] uppercase tracking-[0.28em] text-ust-gold font-semibold';
            break;
    }
?>

<div class="<?= $wrapperClass ?>">
    <div class="<?= $markClass ?>" aria-hidden="true">
        <svg viewBox="0 0 64 64" class="<?= $svgClass ?>" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="8" y="8" width="48" height="48" rx="16" fill="#F4C300"/>
            <path d="M18 18h28v6H35v23h-6V24H18v-6Z" fill="#1A1A1A"/>
            <path d="M19 38c4.2-4.8 8.6-7.2 13.1-7.2 5.4 0 9.9 2.4 13.9 7.2" stroke="#1A1A1A" stroke-width="4.5" stroke-linecap="round"/>
            <path d="M19 45c4.2-4.8 8.6-7.2 13.1-7.2 5.4 0 9.9 2.4 13.9 7.2" stroke="#FFF8D3" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="46" cy="20" r="3.5" fill="#FFF8D3"/>
        </svg>
    </div>
    <div>
        <p class="<?= $titleClass ?>">TRACK</p>
        <p class="<?= $subtitleClass ?>">UST Events Tracker</p>
    </div>
</div>
