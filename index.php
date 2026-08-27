<!--
  ═══════════════════════════════════════════════════════════════
  MAIN GUEST FEEDBACK FORM (FRONTEND)
  This file displays the guest-facing feedback form. It contains
  HTML structure styled with Tailwind CSS, custom animations, 
  and basic JavaScript for form interactivity.
  ═══════════════════════════════════════════════════════════════
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../img/icon.png">
    <title>Guest Feedback - John Hay Hotels | Forest Wing</title>

    <!-- ============================================================
         EXTERNAL RESOURCES
         - Tailwind CSS (CDN) for utility-first styling
         - Google Fonts: Great Vibes, Playfair Display, Inter
    ============================================================ -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- ============================================================
         TAILWIND CONFIGURATION
         Custom color palette (pine greens & gold accents) and fonts
    ============================================================ -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        /* Deep forest green palette */
                        pine: {
                            50: '#f0f7f2', 100: '#daeede', 200: '#b8ddc0', 300: '#88c498',
                            400: '#55a56b', 500: '#348a4f', 600: '#256e3d', 700: '#1e5832',
                            800: '#1B3A2D', 900: '#142B21', 950: '#0A1912',
                        },
                        /* Warm gold accent palette */
                        gold: {
                            50: '#fdf9ef', 100: '#f9f0d5', 200: '#f2dea8', 300: '#e9c872',
                            400: '#C9A96E', 500: '#b5893a', 600: '#a0702e', 700: '#855528',
                        },
                    },
                    fontFamily: {
                        script: ['"Great Vibes"', 'cursive'],
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['"Inter"', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    <!-- ============================================================
         CUSTOM STYLES (organized by component)
    ============================================================ -->
    <style>
        /* ── BASE ── */
        body {
            background: #0A1912;
        }

        /* ── IMMERSIVE BACKGROUND: Fixed forest lodge photo + overlays ── */
        .scene-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 0;
            background: url('img/forest.jpg') center/cover no-repeat;
        }

        .scene-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(10, 25, 18, 0.55) 0%, rgba(10, 25, 18, 0.30) 30%,
                    rgba(10, 25, 18, 0.35) 70%, rgba(10, 25, 18, 0.70) 100%),
                radial-gradient(ellipse at 30% 20%, rgba(201, 169, 110, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 80%, rgba(10, 25, 18, 0.30) 0%, transparent 60%);
        }

        /* ── WARM VIGNETTE: Subtle golden glow from above ── */
        .warm-vignette {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
            background: radial-gradient(ellipse at 50% 0%, rgba(201, 169, 110, 0.04) 0%, transparent 50%);
        }

        /* ── FLOATING PARTICLES: Firefly effect with gold dots ── */
        .particle {
            position: fixed;
            border-radius: 50%;
            pointer-events: none;
            z-index: 1;
        }

        .particle-1 {
            width: 3px;
            height: 3px;
            background: rgba(201, 169, 110, 0.50);
            top: 20%;
            left: 15%;
            animation: float1 8s ease-in-out infinite;
        }

        .particle-2 {
            width: 2px;
            height: 2px;
            background: rgba(201, 169, 110, 0.30);
            top: 45%;
            left: 80%;
            animation: float2 12s ease-in-out infinite;
        }

        .particle-3 {
            width: 4px;
            height: 4px;
            background: rgba(201, 169, 110, 0.20);
            top: 70%;
            left: 25%;
            animation: float3 10s ease-in-out infinite;
        }

        .particle-4 {
            width: 2px;
            height: 2px;
            background: rgba(201, 169, 110, 0.40);
            top: 35%;
            left: 60%;
            animation: float1 14s ease-in-out infinite 2s;
        }

        .particle-5 {
            width: 3px;
            height: 3px;
            background: rgba(201, 169, 110, 0.25);
            top: 60%;
            left: 45%;
            animation: float2 9s ease-in-out infinite 1s;
        }

        .particle-6 {
            width: 2px;
            height: 2px;
            background: rgba(201, 169, 110, 0.35);
            top: 15%;
            left: 70%;
            animation: float3 11s ease-in-out infinite 3s;
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            50% {
                transform: translate(30px, -40px) scale(1.5);
                opacity: 0.7;
            }

            90% {
                opacity: 1;
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0;
            }

            15% {
                opacity: 1;
            }

            50% {
                transform: translate(-20px, -50px) scale(1.3);
                opacity: 0.5;
            }

            85% {
                opacity: 1;
            }
        }

        @keyframes float3 {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
                opacity: 0;
            }

            20% {
                opacity: 0.8;
            }

            50% {
                transform: translate(40px, -30px) scale(1.8);
                opacity: 0.4;
            }

            80% {
                opacity: 0.8;
            }
        }

        /* ── GLASSMORPHISM CARDS ── */
        .glass-card {
            /* Increased opacity from 0.08 to 0.15 for a denser, heavier look */
            background: rgba(255, 255, 255, 0.15);

            /* Increased blur to 45px for a thicker frosted effect */
            backdrop-filter: blur(45px);
            -webkit-backdrop-filter: blur(45px);

            /* Slightly more visible border */
            border: 1px solid rgba(255, 255, 255, 0.25);

            /* Deepened the drop shadow and added a thicker 2px inset highlight for a 3D edge */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35),
                inset 0 2px 2px rgba(255, 255, 255, 0.15);
        }

        .glass-card-warm {
            /* Increased opacity from 0.10 to 0.18 */
            background: rgba(245, 235, 224, 0.18);

            /* Increased blur from 24px to 36px */
            backdrop-filter: blur(36px);
            -webkit-backdrop-filter: blur(36px);

            /* Slightly more visible warm border */
            border: 1px solid rgba(201, 169, 110, 0.25);

            /* Deeper drop shadow and stronger inset edge */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.30),
                inset 0 2px 2px rgba(201, 169, 110, 0.20);
        }

        /* ── CUSTOM SCROLLBAR ── */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(10, 25, 18, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(201, 169, 110, 0.4);
            border-radius: 99px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(201, 169, 110, 0.6);
        }

        /* ── CUSTOM RADIO BUTTONS: Gold circles replacing browser defaults ── */
        .custom-radio input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-radio .radio-mark {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 2px solid rgba(201, 169, 110, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            background: rgba(255, 255, 255, 0.05);
        }

        .custom-radio .radio-mark::after {
            content: '';
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #C9A96E;
            transform: scale(0);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .custom-radio input[type="radio"]:checked+.radio-mark {
            border-color: #C9A96E;
            background: rgba(201, 169, 110, 0.1);
            box-shadow: 0 0 12px rgba(201, 169, 110, 0.2);
        }

        .custom-radio input[type="radio"]:checked+.radio-mark::after {
            transform: scale(1);
        }

        .custom-radio:hover .radio-mark {
            border-color: rgba(201, 169, 110, 0.6);
            background: rgba(201, 169, 110, 0.05);
        }

        /* ── NPS BUTTONS: Numbered circles 1-10 ── */
        .nps-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 2px solid rgba(201, 169, 110, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: rgba(255, 255, 255, 0.04);
        }

        .nps-btn:hover {
            border-color: #C9A96E;
            color: #C9A96E;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(201, 169, 110, 0.15);
            background: rgba(201, 169, 110, 0.08);
        }

        .nps-radio:checked+.nps-btn {
            background: linear-gradient(135deg, #C9A96E, #b5893a);
            border-color: transparent;
            color: #0A1912;
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(201, 169, 110, 0.35);
        }

        /* ── LODGE INPUT FIELDS: Dark transparent with gold accents ── */
        .lodge-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(201, 169, 110, 0.2);
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.85);
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .lodge-input::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .lodge-input:hover {
            border-color: rgba(201, 169, 110, 0.4);
        }

        .lodge-input:focus {
            border-color: #C9A96E;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.1), 0 4px 16px rgba(0, 0, 0, 0.2);
        }

        select.lodge-input {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20'%3E%3Cpath fill='%23C9A96E' d='M7 7l3 3 3-3' stroke='%23C9A96E' stroke-width='1.5' fill='none' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        select.lodge-input option {
            background: #1B3A2D;
            color: #fff;
        }

        textarea.lodge-input {
            resize: none;
        }

        /* ── DATE INPUT SPECIFICS ── */
        input[type="date"].lodge-input {
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            /* Better touch target */
        }

        /* Remove default iOS date styling that might cause "merging" look */
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: rgba(201, 169, 110, 0.2) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23C9A96E' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E") no-repeat center;
            padding: 4px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Ensure the date text is centered on mobile Safari */
        input[type="date"] {
            appearance: none;
            -webkit-appearance: none;
            position: relative;
        }

        /* ── SCROLL REVEAL: Sections fade up on viewport entry ── */
        .reveal-section {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .reveal-section.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── HEADER ANIMATIONS ── */
        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.8;
            }
        }

        @keyframes glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(201, 169, 110, 0.1);
            }

            50% {
                box-shadow: 0 0 40px rgba(201, 169, 110, 0.2);
            }
        }

        .animate-fade-up {
            opacity: 0;
            animation: fadeUp 0.8s ease-out forwards;
        }

        .animate-shimmer {
            animation: shimmer 3s ease-in-out infinite;
        }

        .animate-glow {
            animation: glow 4s ease-in-out infinite;
        }

        /* ── LOADING SPINNER ── */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spinner {
            animation: spin 0.8s linear infinite;
        }
    </style>
</head>

<body class="font-sans antialiased text-white min-h-screen relative">

    <!-- ═══ BACKGROUND LAYERS ═══ -->
    <div class="scene-bg"></div>
    <div class="warm-vignette"></div>

    <!-- Floating firefly particles -->
    <div class="particle particle-1"></div>
    <div class="particle particle-2"></div>
    <div class="particle particle-3"></div>
    <div class="particle particle-4"></div>
    <div class="particle particle-5"></div>
    <div class="particle particle-6"></div>

    <!-- ═══ MAIN CONTENT WRAPPER ═══ -->
    <div class="relative z-10">

        <!-- ═══ HERO HEADER ═══ -->
        <header class="relative py-20 md:py-28 text-center overflow-hidden">
            <div class="relative z-10 px-6">
                <!-- Decorative shimmer line -->
                <div
                    class="w-20 h-px bg-gradient-to-r from-transparent via-gold-400/50 to-transparent mx-auto mb-8 animate-shimmer">
                </div>

                <!-- Hotel name -->
                <h1 class="font-script text-5xl sm:text-6xl md:text-7xl lg:text-8xl text-white/90 mb-2 animate-fade-up drop-shadow-[0_2px_10px_rgba(0,0,0,0.5)]"
                    style="animation-delay: 0.1s;">
                    John Hay Hotels
                </h1>

                <!-- "Forest Wing" badge -->
                <div class="inline-flex items-center gap-4 mt-1 mb-8 animate-fade-up" style="animation-delay: 0.3s;">
                    <span class="w-12 h-px bg-gold-400/40"></span>
                    <span class="text-gold-400/90 text-xs md:text-sm font-semibold tracking-[0.4em] uppercase">Forest
                        Wing</span>
                    <span class="w-12 h-px bg-gold-400/40"></span>
                </div>

                <!-- Diamond divider -->
                <div class="flex items-center justify-center gap-4 mb-10 animate-fade-up"
                    style="animation-delay: 0.45s;">
                    <span class="w-16 h-px bg-gold-400/20"></span>
                    <span class="w-2 h-2 rotate-45 border border-gold-400/40 animate-glow"></span>
                    <span class="w-16 h-px bg-gold-400/20"></span>
                </div>

                <!-- Intro message card -->
                <div class="glass-card rounded-2xl px-8 py-6 max-w-xl mx-auto animate-fade-up"
                    style="animation-delay: 0.6s;">
                    <p class="font-serif italic text-white/60 text-sm md:text-base leading-relaxed">
                        We are committed to provide a guest experience that exceeds your expectations.
                        Through your comments we can build on our strengths and, where necessary, improve our
                        weaknesses.
                    </p>
                </div>
            </div>
        </header>

        <!-- ═══ FEEDBACK FORM ═══ -->
        <main class="max-w-3xl mx-auto px-4 sm:px-6 pb-24">
            <form id="feedbackForm" action="submit_feedback.php" method="POST" class="space-y-8">

                <?php
                /**
                 * Helper function: ratingSection()
                 * Generates a glassmorphism card with rating rows (N/A/Poor/Good/Excellent)
                 * and a comments textarea. Optionally adds extra fields.
                 *
                 * @param string $title       Section heading
                 * @param string $icon        SVG path for the icon
                 * @param array  $items       [input_name => Display Label]
                 * @param string $commentName Name for the textarea
                 * @param string $commentPH   Placeholder for the textarea
                 * @param string $extraFields Optional extra HTML after comments
                 */
                function ratingSection(
                    $title,
                    $icon,
                    $items,
                    $commentName,
                    $commentPH,
                    $extraFields = "",
                ) {
                    ?>
                    <section class="reveal-section glass-card-warm rounded-2xl overflow-hidden">
                        <!-- Section header -->
                        <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24"><?= $icon ?></svg>
                            </div>
                            <h2 class="font-serif text-white/80 text-lg tracking-wider uppercase"><?= $title ?></h2>
                        </div>

                        <div class="px-6 py-6">
                            <!-- Column headers (desktop only) -->
                            <div class="hidden md:grid grid-cols-[1fr_80px_80px_80px_80px] gap-2 mb-3 px-2">
                                <span></span>
                                <span
                                    class="text-center text-[0.6rem] font-bold text-white uppercase tracking-[0.15em]">Excellent</span>
                                <span
                                    class="text-center text-[0.6rem] font-bold text-white uppercase tracking-[0.15em]">Good</span>
                                <span
                                    class="text-center text-[0.6rem] font-bold text-white uppercase tracking-[0.15em]">Poor</span>
                                <span
                                    class="text-center text-[0.6rem] font-bold text-white uppercase tracking-[0.15em]">N/A</span>
                            </div>

                            <!-- Rating rows -->
                            <?php foreach ($items as $name => $label): ?>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-[1fr_80px_80px_80px_80px] gap-2 items-center py-3 border-b border-gold-400/60 last:border-b-0 hover:bg-white/10 active:bg-white/10 rounded-lg px-2 transition-colors duration-300">
                                    <span
                                        class="font-medium text-sm text-gold-400/100 text-center md:text-left"><?= $label ?></span>
                                    <div class="flex md:contents justify-center gap-8 md:gap-0">
                                        <?php foreach (
                                            [
                                                ["3", "Excellent"],
                                                ["2", "Good"],
                                                ["1", "Poor"],
                                                ["0", "N/A"],
                                            ]
                                            as $rating
                                        ): ?>

                                            <div class="flex flex-col items-center gap-1">
                                                <span
                                                    class="text-[0.55rem] text-white font-semibold uppercase md:hidden tracking-wider"><?= $rating[1] ?></span>
                                                <label class="custom-radio">
                                                    <input type="radio" name="<?= $name ?>" value="<?= $rating[0] ?>"
                                                        <?= $rating[0] ===
                                                            "0"
                                                            ? "required"
                                                            : "" ?>>
                                                    <span class="radio-mark"></span>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if ($commentName !== ""): ?>
                                <!-- Comments textarea (if enabled) -->
                                <div class="mt-6">
                                    <label
                                        class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Comments
                                        &amp; Suggestions</label>
                                    <textarea name="<?= $commentName ?>" rows="3" placeholder="<?= $commentPH ?>"
                                        class="lodge-input"></textarea>
                                </div>
                            <?php endif; ?>

                            <?php if ($extraFields) {
                                echo $extraFields;
                            } ?>
                        </div>
                    </section>
                    <?php
                }

                // ═══ SECTION 1: HOW DID YOU FIND OUT & MODE OF RESERVATION ═══
                ?>
                <section class="reveal-section glass-card-warm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="font-serif text-white/80 text-lg tracking-wider uppercase">We would also like to know
                            you...</h2>
                    </div>
                    <div class="px-6 py-6 grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- How did you find out about us? -->
                        <div>
                            <label
                                class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-4 drop-shadow-sm">
                                How did you find out about us? <span class="text-red-400">*</span>
                            </label>
                            <div class="space-y-3">
                                <?php
                                $findOutOptions = ['Print Advertisement', 'Radio', 'Internet', 'Hotel Website', 'Travel Agency'];
                                foreach ($findOutOptions as $option):
                                    ?>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="custom-radio"><input type="radio" name="find_out_about_us"
                                                value="<?= $option ?>" required onchange="toggleOtherFindOut()"><span
                                                class="radio-mark"></span></div>
                                        <span
                                            class="text-sm text-white/70 group-hover:text-gold-400 transition-colors"><?= $option ?></span>
                                    </label>
                                <?php endforeach; ?>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="custom-radio"><input type="radio" name="find_out_about_us"
                                            value="Others" id="find_out_others_radio"
                                            onchange="toggleOtherFindOut()"><span class="radio-mark"></span></div>
                                    <span
                                        class="text-sm text-white/70 group-hover:text-gold-400 transition-colors">Others,
                                        Please specify</span>
                                </label>
                                <input type="text" id="other_find_out" name="other_find_out_text"
                                    placeholder="Specify here..." class="lodge-input mt-2 hidden">
                            </div>
                        </div>

                        <!-- Mode of reservation -->
                        <div>
                            <label
                                class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-4 drop-shadow-sm">
                                Mode of reservation <span class="text-red-400">*</span>
                            </label>
                            <div class="space-y-3">
                                <?php
                                $reservationOptions = ['Phone', 'Walk-in', 'Hotel Website', 'Email', 'Travel Agency'];
                                foreach ($reservationOptions as $option):
                                    ?>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="custom-radio"><input type="radio" name="mode_of_reservation"
                                                value="<?= $option ?>" required><span class="radio-mark"></span></div>
                                        <span
                                            class="text-sm text-white/70 group-hover:text-gold-400 transition-colors"><?= $option ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </section>
                <?php

                // ═══ SECTION 2: OUR HOTEL PROCESS ═══
                ratingSection(
                    "Our Hotel Process",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                    [
                        "reservations" => "Reservation",
                        "check_in_rating" => "Check-In",
                        "check_out_rating" => "Check-Out",
                        "accommodation" => "Accommodation"
                    ],
                    "",
                    "",
                );

                // ═══ SECTION 3: OUR ASSOCIATES ═══
                ratingSection(
                    "Our Associates",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />',
                    [
                        "telephone_operator" => "Telephone Operator",
                        "reservations_associate" => "Reservations Associate", // Mapped to frontdesk for now in db, but renaming frontdesk to fit
                        "frontdesk" => "Front Office Agents",
                        "housekeeping" => "Housekeeping",
                        "security" => "Security",
                    ],
                    "",
                    "",
                );

                // ═══ SECTION 4: OUR GUESTROOM ═══
                ratingSection(
                    "Our Guestroom",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />',
                    [
                        "cleanliness" => "Cleanliness",
                        "ambiance" => "Ambiance",
                        "comfort" => "Comfort",
                        "bathroom" => "Bathroom",
                    ],
                    "",
                    "",
                );

                // ═══ SECTION 5: FOOD & BEVERAGE FACILITIES ═══
                ratingSection(
                    "Our Food and Beverage Facilities",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 2v2 M14 2v2 M16 8a1 1 0 0 1 1 1v8a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V9a1 1 0 0 1 1-1h14a4 4 0 1 1 0 8h-1 M6 2v2"/>',
                    [
                        "food_quality" => "Food Quality",
                        "serving_time" => "Serving Time",
                        "grooming" => "Waiter/s Grooming",
                        "behavior" => "Waiter/s Behavior",
                        "fnb_service" => "Waiter/s Service",
                        "bar" => "Bar",
                    ],
                    "",
                    "",
                );

                // ═══ SECTION 6: FOREST WING HOSPITALITY ═══
                ratingSection(
                    "Forest Wing Hospitality",
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />',
                    [
                        "friendliness" => "Friendliness",
                        "attentiveness" => "Attentiveness",
                        "courteousness" => "Courteousness",
                    ],
                    "",
                    "",
                );
                ?>
                <!-- ═══ OVERALL EXPERIENCE (NPS 1-10) ═══ -->
                <section class="reveal-section glass-card-warm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                        <h2 class="font-serif text-white/80 text-lg tracking-wider uppercase">Overall Service Rating
                        </h2>
                    </div>
                    <div class="px-6 py-8">
                        <p class="text-sm font-medium text-white/60 mb-8 text-center">
                            Based on your experience, how satisfied are you with your stay? <span
                                class="text-red-400">*</span>
                        </p>
                        <!-- NPS numbered circles -->
                        <div class="flex justify-center gap-2 sm:gap-3 flex-wrap">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="overall_rating" value="<?= $i ?>" <?= $i ===
                                          1
                                          ? "required"
                                          : "" ?> class="nps-radio sr-only">
                                    <span class="nps-btn"><?= $i ?></span>
                                </label>
                            <?php endfor; ?>
                        </div>
                        <div class="flex justify-between mt-5 px-2">
                            <span class="text-[0.6rem] text-white/50 uppercase tracking-[0.2em] font-medium">Not
                                Satisfied</span>
                            <span class="text-[0.6rem] text-white/50 uppercase tracking-[0.2em] font-medium">Highly
                                Satisfied</span>
                        </div>
                    </div>
                </section>

                <!-- ═══ COMMENTS & SUGGESTIONS ═══ -->
                <section class="reveal-section glass-card-warm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                        </div>
                        <h2 class="font-serif text-white/80 text-lg tracking-wider uppercase">Comments / Suggestions
                        </h2>
                    </div>
                    <div class="px-6 py-6 space-y-8">
                        <div>
                            <label
                                class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Please
                                let us know the name/s of any of our hotel staff who were especially helpful.</label>
                            <input type="text" name="helpful_staff_names" placeholder="Name(s) of staff members"
                                class="lodge-input">
                        </div>
                        <div>
                            <label
                                class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Do
                                you have any other suggestions or comments which would help us make your next visit more
                                enjoyable?</label>
                            <textarea name="general_comments" rows="4" placeholder="Share your thoughts here..."
                                class="lodge-input"></textarea>
                        </div>

                        <!-- Repeat Visit -->
                        <div class="border-t border-gold-400/20 pt-6">
                            <p class="text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-4">
                                Will you stay with us again?
                            </p>
                            <div class="flex gap-8 justify-start">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="custom-radio"><input type="radio" name="repeat_visit" value="Yes"><span
                                            class="radio-mark"></span></div>
                                    <span
                                        class="text-sm text-white/70 group-hover:text-gold-400 transition-colors">Yes</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="custom-radio"><input type="radio" name="repeat_visit" value="No"><span
                                            class="radio-mark"></span></div>
                                    <span
                                        class="text-sm text-white/70 group-hover:text-gold-400 transition-colors">No</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- ═══ SECTION 5: YOUR INFORMATION ═══ -->
                <section class="reveal-section glass-card-warm rounded-2xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h2 class="font-serif text-white/80 text-lg tracking-wider uppercase">Your Information</h2>
                    </div>
                    <div class="px-6 py-6">
                        <!-- First stay question -->
                        <div class="mb-6">
                            <p class="text-[0.65rem] font-semibold text-gold-400/100 uppercase tracking-[0.15em] mb-3">
                                Was this your first stay at John Hay Hotels? <span class="text-red-400">*</span>
                            </p>
                            <div class="flex gap-8 justify-center md:justify-start">
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <label class="custom-radio"><input type="radio" name="first_stay" value="Yes"
                                            required><span class="radio-mark"></span></label>
                                    <span
                                        class="text-sm text-white/50 group-hover:text-gold/100 transition-colors">Yes</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <label class="custom-radio"><input type="radio" name="first_stay" value="No"><span
                                            class="radio-mark"></span></label>
                                    <span
                                        class="text-sm text-white/50 group-hover:text-gold/100 transition-colors">No</span>
                                </label>
                            </div>
                        </div>

                        <!-- Purpose of stay -->
                        <div class="mb-6">
                            <label
                                class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-4 drop-shadow-sm">
                                Purpose of visit <span class="text-red-400">*</span>
                            </label>
                            <div class="space-y-3">
                                <?php
                                $purposeOptions = ['Business', 'Holiday'];
                                foreach ($purposeOptions as $option):
                                    ?>
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="custom-radio"><input type="radio" name="purpose_of_stay"
                                                value="<?= $option ?>" required onchange="toggleOtherPurpose()"><span
                                                class="radio-mark"></span></div>
                                        <span
                                            class="text-sm text-white/70 group-hover:text-gold-400 transition-colors"><?= $option ?></span>
                                    </label>
                                <?php endforeach; ?>
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="custom-radio"><input type="radio" name="purpose_of_stay" value="Others"
                                            id="purpose_others_radio" onchange="toggleOtherPurpose()"><span
                                            class="radio-mark"></span></div>
                                    <span
                                        class="text-sm text-white/70 group-hover:text-gold-400 transition-colors">Others,
                                        Please specify</span>
                                </label>
                                <input type="text" id="other_purpose" name="other_purpose_text"
                                    placeholder="Specify here..." class="lodge-input mt-2 hidden">
                            </div>
                        </div>

                        <!-- Nationality -->
                        <div class="mb-6">
                            <label
                                class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">
                                Nationality <span class="text-red-400">*</span>
                            </label>
                            <select name="nationality" id="nationality_dropdown" onchange="toggleOtherNationality()"
                                required class="lodge-input">
                                <option value="Filipino" selected>Filipino</option>
                                <option value="American">American</option>
                                <option value="Chinese">Chinese</option>
                                <option value="Japanese">Japanese</option>
                                <option value="Korean">Korean</option>
                                <option value="British">British</option>
                                <option value="Australian">Australian</option>
                                <option value="Canadian">Canadian</option>
                                <option value="Russian">Russian</option>
                                <option value="Indian">Indian</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" id="other_nationality" name="other_nationality_text"
                                placeholder="Please specify your nationality" class="lodge-input mt-3 hidden">
                        </div>

                        <!-- Divider -->
                        <div class="flex items-center gap-4 my-8">
                            <span
                                class="flex-1 h-px bg-gradient-to-r from-transparent via-gold-400/40 to-transparent"></span>
                            <span class="text-[0.6rem] font-bold text-gold-400/50 uppercase tracking-[0.25em]">Your
                                Details</span>
                            <span
                                class="flex-1 h-px bg-gradient-to-r from-transparent via-gold-400/20 to-transparent"></span>
                        </div>

                        <!-- Guest details grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label
                                    class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Name
                                    <span class="text-red-400">*</span></label>
                                <input type="text" name="guest_name" placeholder="Your full name" required
                                    class="lodge-input">
                            </div>
                            <div>
                                <label
                                    class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Email
                                    <span class="text-red-400">*</span></label>
                                <input type="text" name="email" placeholder="your.email@example.com" required
                                    class="lodge-input">
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Address
                                    <span class="text-red-400">*</span></label>
                                <input type="text" name="address" placeholder="Your complete address" required
                                    class="lodge-input">
                            </div>
                            <div>
                                <label
                                    class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Contact
                                    No. <span class="text-red-400">*</span> </label>
                                <input type="tel" name="contact_no" placeholder="+63 917 123 4567" required
                                    class="lodge-input">
                            </div>
                            <div>
                                <label
                                    class="block text-[0.75rem] font-bold text-gold-400 uppercase tracking-[0.15em] mb-2 drop-shadow-sm">Room
                                    No. <span class="text-red-400">*</span></label>
                                <input type="text" name="room_no" placeholder="e.g. 205" required class="lodge-input">
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="block text-[0.65rem] font-semibold text-white uppercase tracking-[0.15em] mb-3">
                                    Date(s) of Stay <span class="text-red-400">*</span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <span
                                            class="block text-[0.6rem] text-gold-500/90 font-bold uppercase tracking-wider pl-1">Check-in</span>
                                        <input type="date" name="check_in" id="check_in" onchange="setMinCheckout()"
                                            required class="lodge-input">
                                    </div>
                                    <div class="space-y-1">
                                        <span
                                            class="block text-[0.6rem] text-gold-500/90 font-bold uppercase tracking-wider pl-1">Check-out</span>
                                        <input type="date" name="check_out" id="check_out" required class="lodge-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ═══ SUBMIT BUTTON ═══ -->
                <div class="reveal-section text-center pt-6 pb-4">
                    <div class="glass-card-warm rounded-2xl px-8 py-6 max-w-2xl mx-auto mb-10">
                        <p
                            class="font-serif italic text-white/90 text-lg md:text-xl drop-shadow-lg font-medium leading-relaxed">
                            Thank you for staying with us. We look forward to welcoming you again to John Hay Hotels.
                        </p>
                    </div>
                    <button type="submit" id="submitBtn"
                        class="group relative inline-flex items-center justify-center gap-3 px-10 py-3.5 rounded-full font-semibold text-[0.8rem] uppercase tracking-[0.2em] transition-all duration-500 w-full sm:w-auto sm:min-w-[260px] overflow-hidden"
                        style="background:linear-gradient(135deg,#C9A96E 0%,#b5893a 50%,#C9A96E 100%);color:#0A1912;box-shadow:0 8px 32px rgba(201,169,110,0.25)">
                        <span
                            class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></span>
                        <div id="btnLoader" class="hidden relative z-10">
                            <svg class="w-5 h-5 spinner" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4" />
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
                            </svg>
                        </div>
                        <span id="btnText" class="relative z-10">Submit Feedback</span>
                        <svg id="btnArrow"
                            class="w-4 h-4 relative z-10 group-hover:translate-x-1 transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>

            </form>
        </main>

        <!-- ═══ FOOTER ═══ -->
        <footer class="relative z-10 text-center py-12 border-t border-white/10 bg-black/20 backdrop-blur-sm">
            <p class="font-script text-4xl text-gold-400/90 mb-3 drop-shadow-md">
                John Hay Hotels
            </p>

            <p class="text-white/60 text-[0.7rem] font-medium uppercase tracking-[0.5em]">
                Forest Wing - Camp John Hay - Baguio City, 2600
            </p>
        </footer>
    </div>

    <!-- ═══ JAVASCRIPT ═══ -->
    <script>
        /** Toggle "Other" purpose text field visibility */
        function toggleOtherPurpose() {
            var r = document.getElementById("purpose_others_radio");
            var o = document.getElementById("other_purpose");
            if (r.checked) {
                o.classList.remove("hidden"); o.required = true; o.focus();
            } else {
                o.classList.add("hidden"); o.required = false; o.value = "";
            }
        }

        /** Toggle "Other" find out text field visibility */
        function toggleOtherFindOut() {
            var r = document.getElementById("find_out_others_radio");
            var o = document.getElementById("other_find_out");
            if (r.checked) {
                o.classList.remove("hidden"); o.required = true; o.focus();
            } else {
                o.classList.add("hidden"); o.required = false; o.value = "";
            }
        }

        /** Toggle "Other" nationality text field visibility */
        function toggleOtherNationality() {
            var d = document.getElementById("nationality_dropdown");
            var o = document.getElementById("other_nationality");
            if (d.value === "Other") {
                o.classList.remove("hidden"); o.required = true; o.focus();
            } else {
                o.classList.add("hidden"); o.required = false; o.value = "";
            }
        }

        /** Set minimum checkout date to match check-in */
        function setMinCheckout() {
            document.getElementById("check_out").min = document.getElementById("check_in").value;
        }

        /** Form submit handler: show spinner, disable button */
        document.getElementById("feedbackForm").addEventListener("submit", function (e) {
            if (!this.checkValidity()) return;
            var b = document.getElementById("submitBtn");
            var l = document.getElementById("btnLoader");
            var t = document.getElementById("btnText");
            var a = document.getElementById("btnArrow");
            b.disabled = true; b.style.opacity = "0.7"; b.style.cursor = "not-allowed";
            l.classList.remove("hidden"); a.classList.add("hidden");
            t.innerText = "Submitting...";
        });

        /** IntersectionObserver: fade-in sections on scroll */
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.06, rootMargin: "0px 0px -60px 0px" });

        document.querySelectorAll(".reveal-section").forEach(function (s) {
            observer.observe(s);
        });
    </script>
</body>

</html>