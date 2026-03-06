<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN ANALYTICS DASHBOARD
 * John Hay Hotels - Forest Wing Guest Feedback System
 * ═══════════════════════════════════════════════════════════════
 */
session_start();
require_once "../config.php";

if (
!isset($_SESSION["admin_logged_in"]) ||
$_SESSION["admin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Admin - John Hay Hotels</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pine: { 700: '#1e5832', 800: '#1B3A2D', 900: '#142B21', 950: '#0A1912' },
                        gold: { 400: '#C9A96E', 500: '#b5893a' },
                    },
                    fontFamily: {
                        script: ['"Great Vibes"', 'cursive'],
                        serif:  ['"Playfair Display"', 'serif'],
                        sans:   ['"Inter"', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { background: #0A1912; }
        .glass-card {
            background: rgba(245,235,224,0.06);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(201,169,110,0.12);
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(10,25,18,0.5); }
        ::-webkit-scrollbar-thumb { background: rgba(201,169,110,0.3); border-radius: 99px; }
        .period-btn {
            padding: 8px 16px; border-radius: 10px; font-size: 0.75rem;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;
            border: 1px solid rgba(201,169,110,0.15); color: rgba(255,255,255,0.4);
            background: transparent; cursor: pointer; transition: all 0.3s ease;
        }
        .period-btn:hover { border-color: rgba(201,169,110,0.4); color: rgba(255,255,255,0.7); }
        .period-btn.active {
            background: linear-gradient(135deg, #C9A96E, #b5893a);
            color: #0A1912; border-color: transparent;
        }
        @keyframes fadeUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .fade-up { opacity: 0; animation: fadeUp 0.5s ease-out forwards; }
        .chart-container { position: relative; width: 100%; }
        .loading-overlay {
            position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
            background: rgba(10,25,18,0.8); border-radius: inherit; z-index: 10;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { width: 32px; height: 32px; border: 3px solid rgba(201,169,110,0.2);
            border-top-color: #C9A96E; border-radius: 50%; animation: spin 0.8s linear infinite; }
        .stat-value { font-variant-numeric: tabular-nums; }

        /* Nav link active state */
        .nav-link { padding: 6px 14px; border-radius: 8px; font-size: 0.75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s ease; }
        .nav-link:hover { background: rgba(201,169,110,0.08); color: rgba(255,255,255,0.7); }
        .nav-link.active { background: rgba(201,169,110,0.12); color: #C9A96E; }
    </style>
</head>
<body class="font-sans text-white min-h-screen">

    <!-- ═══ TOP NAV BAR ═══ -->
    <nav class="border-b border-white/[0.06] px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="font-script text-3xl text-white/70">John Hay Hotels</h1>
                <span class="text-[0.55rem] font-bold text-gold-400/50 uppercase tracking-[0.2em] px-3 py-1 rounded-full border border-gold-400/20">Admin</span>
            </div>
            <div class="flex items-center gap-2">
                <a href="index.php" class="nav-link text-white/40">Dashboard</a>
                <a href="analytics.php" class="nav-link active">Analytics</a>
                <a href="reports.php" class="nav-link text-white/40">Reports</a>
                <span class="text-white/10 mx-2">|</span>
                <a href="logout.php" class="text-sm text-white/30 hover:text-red-400/70 transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- ═══ PAGE HEADER + PERIOD FILTERS ═══ -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8 fade-up">
            <div>
                <h2 class="font-serif text-2xl text-white/80 tracking-wide">Feedback Analytics</h2>
                <p class="text-white/30 text-sm mt-1">Visual summary of guest feedback and satisfaction scores</p>
            </div>
            <div class="flex flex-wrap gap-2" id="periodFilters">
                <button class="period-btn" data-period="today">Today</button>
                <button class="period-btn" data-period="week">This Week</button>
                <button class="period-btn" data-period="month">This Month</button>
                <button class="period-btn" data-period="quarter">Quarter</button>
                <button class="period-btn active" data-period="all">All Time</button>
            </div>
        </div>

        <!-- ═══ SUMMARY STAT CARDS ═══ -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8 fade-up" style="animation-delay:0.1s">
            <!-- Total Responses -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <span class="text-[0.65rem] font-semibold text-gold-400/60 uppercase tracking-[0.15em]">Total Responses</span>
                </div>
                <p class="text-3xl font-bold text-white/80 stat-value" id="statTotal">—</p>
            </div>
            <!-- Average NPS -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <span class="text-[0.65rem] font-semibold text-gold-400/60 uppercase tracking-[0.15em]">Avg. Satisfaction</span>
                </div>
                <p class="text-3xl font-bold text-white/80 stat-value"><span id="statNps">—</span><span class="text-lg text-white/30">/10</span></p>
            </div>
            <!-- FOH Score -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="text-[0.65rem] font-semibold text-gold-400/60 uppercase tracking-[0.15em]">Avg. FOH Score</span>
                </div>
                <p class="text-3xl font-bold text-white/80 stat-value"><span id="statFoh">—</span><span class="text-lg text-white/30">/10</span></p>
            </div>
            <!-- F&B Score -->
            <div class="glass-card rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-[0.65rem] font-semibold text-gold-400/60 uppercase tracking-[0.15em]">Avg. F&B Score</span>
                </div>
                <p class="text-3xl font-bold text-white/80 stat-value"><span id="statFnb">—</span><span class="text-lg text-white/30">/10</span></p>
            </div>
        </div>

        <!-- ═══ CHARTS ROW 1: NPS Trend + NPS Distribution ═══ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- NPS Trend (large) -->
            <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.15s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Satisfaction Score Trend</h3>
                </div>
                <div class="p-6 chart-container" style="height: 320px;">
                    <div class="loading-overlay" id="loadingTrend"><div class="spinner"></div></div>
                    <canvas id="chartNpsTrend"></canvas>
                </div>
            </div>
            <!-- NPS Distribution -->
            <div class="glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.2s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Rating Distribution</h3>
                </div>
                <div class="p-6 chart-container" style="height: 320px;">
                    <div class="loading-overlay" id="loadingDist"><div class="spinner"></div></div>
                    <canvas id="chartNpsDist"></canvas>
                </div>
            </div>
        </div>

        <!-- ═══ CHARTS ROW 2: FOH + F&B Performance ═══ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Front of House -->
            <div class="glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.25s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Front of House Performance</h3>
                </div>
                <div class="p-6 chart-container" style="height: 360px;">
                    <div class="loading-overlay" id="loadingFoh"><div class="spinner"></div></div>
                    <canvas id="chartFoh"></canvas>
                </div>
            </div>
            <!-- Food & Beverage -->
            <div class="glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.3s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Food & Beverage Performance</h3>
                </div>
                <div class="p-6 chart-container" style="height: 360px;">
                    <div class="loading-overlay" id="loadingFnb"><div class="spinner"></div></div>
                    <canvas id="chartFnb"></canvas>
                </div>
            </div>
        </div>

        <!-- ═══ CHARTS ROW 3: Daily Volume + Purpose + Guest Type ═══ -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Daily Volume -->
            <div class="lg:col-span-2 glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.35s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Daily Feedback Volume</h3>
                </div>
                <div class="p-6 chart-container" style="height: 280px;">
                    <div class="loading-overlay" id="loadingVolume"><div class="spinner"></div></div>
                    <canvas id="chartVolume"></canvas>
                </div>
            </div>
            <!-- Guest Type (First Stay / Returning) -->
            <div class="glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.4s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Guest Type</h3>
                </div>
                <div class="p-6 chart-container" style="height: 280px;">
                    <div class="loading-overlay" id="loadingGuest"><div class="spinner"></div></div>
                    <canvas id="chartGuestType"></canvas>
                </div>
            </div>
        </div>

        <!-- ═══ CHART ROW 4: Purpose of Stay ═══ -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="glass-card rounded-xl overflow-hidden fade-up" style="animation-delay:0.45s">
                <div class="px-6 py-4 border-b border-white/[0.06] flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-400/10 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-serif text-white/70 text-base tracking-wider uppercase">Purpose of Stay</h3>
                </div>
                <div class="p-6 chart-container" style="height: 300px;">
                    <div class="loading-overlay" id="loadingPurpose"><div class="spinner"></div></div>
                    <canvas id="chartPurpose"></canvas>
                </div>
            </div>
            <!-- Empty info card -->
            <div class="glass-card rounded-xl overflow-hidden fade-up flex flex-col items-center justify-center p-8 text-center" style="animation-delay:0.5s" id="noDataCard">
                <div class="w-16 h-16 rounded-full bg-gold-400/10 flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gold-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h4 class="font-serif text-white/60 text-lg mb-2">Analytics Overview</h4>
                <p class="text-white/30 text-sm leading-relaxed max-w-xs">
                    Charts update in real-time as guests submit feedback. Use the period filters above to analyze specific time ranges.
                </p>
                <div class="mt-6 flex items-center gap-3 text-xs text-white/20">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-400/60"></span> Excellent (9-10)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-400/60"></span> Good (7-8)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-yellow-400/60"></span> Average (5-6)
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-red-400/60"></span> Poor (1-4)
                    </span>
                </div>
            </div>
        </div>

    </div><!-- /max-w-7xl -->

    <!-- Footer -->
    <footer class="text-center py-6 border-t border-white/[0.04] mt-8">
        <p class="text-white/15 text-[0.55rem] uppercase tracking-[0.3em]">John Hay Hotels - Forest Wing Admin Panel</p>
    </footer>

    <!-- ═══ CHART.JS INITIALIZATION ═══ -->
    <script>
    (function() {
        // ─── Color Palette (John Hay Hotels theme) ───
        const GOLD = '#C9A96E';
        const GOLD_LIGHT = 'rgba(201,169,110,0.7)';
        const GOLD_BG = 'rgba(201,169,110,0.15)';
        const COLORS = {
            primary: GOLD,
            grid: 'rgba(255,255,255,0.06)',
            text: 'rgba(255,255,255,0.4)',
            textLight: 'rgba(255,255,255,0.25)',
        };
        const CHART_COLORS = [
            '#C9A96E', '#6ECFC9', '#C96E8A', '#6EC96E', '#8A6EC9',
            '#C9B96E', '#6E9FC9', '#C96EC9', '#9FC96E', '#6EC9A9'
        ];
        const NPS_COLORS = [
            '#ef4444', '#f97316', '#f97316', '#eab308', '#eab308',
            '#84cc16', '#84cc16', '#22c55e', '#10b981', '#059669'
        ];

        // ─── Chart.js Global Defaults ───
        Chart.defaults.color = COLORS.text;
        Chart.defaults.font.family = '"Inter", sans-serif';
        Chart.defaults.font.size = 11;
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.pointStyle = 'circle';
        Chart.defaults.plugins.legend.labels.padding = 16;

        // ─── Chart instances ───
        let charts = {};

        // ─── Helper: hide loading overlay ───
        function hideLoading(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'none';
        }

        // ─── Helper: show loading overlay ───
        function showLoading(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'flex';
        }

        // ─── Destroy all charts ───
        function destroyCharts() {
            Object.keys(charts).forEach(function(key) {
                if (charts[key]) { charts[key].destroy(); charts[key] = null; }
            });
        }

        // ─── Build all charts from API data ───
        function buildCharts(data) {
            destroyCharts();

            // Update stat cards
            document.getElementById('statTotal').textContent = data.summary.total_responses || 0;
            document.getElementById('statNps').textContent = data.summary.avg_nps !== null ? data.summary.avg_nps : '—';

            // Calculate FOH average
            var fohItems = data.front_of_house || [];
            var fohSum = 0, fohCount = 0;
            fohItems.forEach(function(item) { if (item.avg > 0) { fohSum += item.avg; fohCount++; } });
            document.getElementById('statFoh').textContent = fohCount > 0 ? (fohSum / fohCount).toFixed(1) : '—';

            // Calculate F&B average
            var fnbItems = data.food_beverage || [];
            var fnbSum = 0, fnbCount = 0;
            fnbItems.forEach(function(item) { if (item.avg > 0) { fnbSum += item.avg; fnbCount++; } });
            document.getElementById('statFnb').textContent = fnbCount > 0 ? (fnbSum / fnbCount).toFixed(1) : '—';

            // ── 1. NPS TREND (Line Chart) ──
            var trendLabels = (data.nps_trend || []).map(function(d) { return d.date; });
            var trendValues = (data.nps_trend || []).map(function(d) { return parseFloat(d.avg_rating); });
            hideLoading('loadingTrend');
            charts.trend = new Chart(document.getElementById('chartNpsTrend'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Avg. Satisfaction',
                        data: trendValues,
                        borderColor: GOLD,
                        backgroundColor: GOLD_BG,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointBackgroundColor: GOLD,
                        pointBorderColor: '#0A1912',
                        pointBorderWidth: 2,
                        pointHoverRadius: 6,
                        borderWidth: 2.5,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: {
                        x: { grid: { color: COLORS.grid }, ticks: { maxTicksLimit: 12 } },
                        y: { min: 0, max: 10, grid: { color: COLORS.grid },
                            ticks: { stepSize: 2 } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                            titleColor: '#C9A96E',
                            bodyColor: 'rgba(255,255,255,0.8)',
                            padding: 12,
                            cornerRadius: 8,
                        }
                    }
                }
            });

            // ── 2. NPS DISTRIBUTION (Bar Chart) ──
            var distLabels = Object.keys(data.nps_distribution || {});
            var distValues = Object.values(data.nps_distribution || {});
            hideLoading('loadingDist');
            charts.dist = new Chart(document.getElementById('chartNpsDist'), {
                type: 'bar',
                data: {
                    labels: distLabels.map(function(l) { return l + '/10'; }),
                    datasets: [{
                        data: distValues,
                        backgroundColor: NPS_COLORS,
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    indexAxis: 'x',
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: COLORS.grid }, beginAtZero: true,
                            ticks: { stepSize: 1 } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(ctx) { return ctx.parsed.y + ' response' + (ctx.parsed.y !== 1 ? 's' : ''); }
                            }
                        }
                    }
                }
            });

            // ── 3. FRONT OF HOUSE (Horizontal Bar) ──
            hideLoading('loadingFoh');
            charts.foh = new Chart(document.getElementById('chartFoh'), {
                type: 'bar',
                data: {
                    labels: fohItems.map(function(i) { return i.label; }),
                    datasets: [{
                        label: 'Avg. Score',
                        data: fohItems.map(function(i) { return i.avg; }),
                        backgroundColor: fohItems.map(function(i) {
                            if (i.avg >= 8) return 'rgba(16,185,129,0.7)';
                            if (i.avg >= 5) return 'rgba(234,179,8,0.7)';
                            return 'rgba(239,68,68,0.7)';
                        }),
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: { min: 0, max: 10, grid: { color: COLORS.grid },
                            ticks: { stepSize: 2 }
                        },
                        y: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(ctx) {
                                    var v = ctx.parsed.x;
                                    var label = v >= 8 ? 'Excellent' : v >= 5 ? 'Good' : 'Poor';
                                    return v.toFixed(1) + '/10 (' + label + ')';
                                }
                            }
                        }
                    }
                }
            });

            // ── 4. FOOD & BEVERAGE (Horizontal Bar) ──
            hideLoading('loadingFnb');
            charts.fnb = new Chart(document.getElementById('chartFnb'), {
                type: 'bar',
                data: {
                    labels: fnbItems.map(function(i) { return i.label; }),
                    datasets: [{
                        label: 'Avg. Score',
                        data: fnbItems.map(function(i) { return i.avg; }),
                        backgroundColor: fnbItems.map(function(i) {
                            if (i.avg >= 8) return 'rgba(16,185,129,0.7)';
                            if (i.avg >= 5) return 'rgba(234,179,8,0.7)';
                            return 'rgba(239,68,68,0.7)';
                        }),
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    indexAxis: 'y',
                    scales: {
                        x: { min: 0, max: 10, grid: { color: COLORS.grid },
                            ticks: { stepSize: 2 }
                        },
                        y: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(ctx) {
                                    var v = ctx.parsed.x;
                                    var label = v >= 8 ? 'Excellent' : v >= 5 ? 'Good' : 'Poor';
                                    return v.toFixed(1) + '/10 (' + label + ')';
                                }
                            }
                        }
                    }
                }
            });

            // ── 5. DAILY VOLUME (Bar Chart) ──
            var volLabels = (data.daily_volume || []).map(function(d) { return d.date; });
            var volValues = (data.daily_volume || []).map(function(d) { return parseInt(d.count); });
            hideLoading('loadingVolume');
            charts.volume = new Chart(document.getElementById('chartVolume'), {
                type: 'bar',
                data: {
                    labels: volLabels,
                    datasets: [{
                        label: 'Submissions',
                        data: volValues,
                        backgroundColor: GOLD_BG,
                        borderColor: GOLD,
                        borderWidth: 1.5,
                        borderRadius: 4,
                        barPercentage: 0.7,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    scales: {
                        x: { grid: { display: false }, ticks: { maxTicksLimit: 15 } },
                        y: { grid: { color: COLORS.grid }, beginAtZero: true,
                            ticks: { stepSize: 1 } }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                        }
                    }
                }
            });

            // ── 6. GUEST TYPE (Doughnut) ──
            var guestLabels = (data.first_stay || []).map(function(d) { return d.type; });
            var guestValues = (data.first_stay || []).map(function(d) { return parseInt(d.count); });
            hideLoading('loadingGuest');
            charts.guest = new Chart(document.getElementById('chartGuestType'), {
                type: 'doughnut',
                data: {
                    labels: guestLabels,
                    datasets: [{
                        data: guestValues,
                        backgroundColor: ['#C9A96E', '#6ECFC9', '#C96E8A'],
                        borderColor: '#0A1912',
                        borderWidth: 3,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '55%',
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 16 } },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                        }
                    }
                }
            });

            // ── 7. PURPOSE OF STAY (Pie) ──
            var purLabels = (data.purpose_breakdown || []).map(function(d) { return d.purpose; });
            var purValues = (data.purpose_breakdown || []).map(function(d) { return parseInt(d.count); });
            hideLoading('loadingPurpose');
            charts.purpose = new Chart(document.getElementById('chartPurpose'), {
                type: 'pie',
                data: {
                    labels: purLabels,
                    datasets: [{
                        data: purValues,
                        backgroundColor: CHART_COLORS.slice(0, purLabels.length),
                        borderColor: '#0A1912',
                        borderWidth: 3,
                        hoverOffset: 8,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 12 } },
                        tooltip: {
                            backgroundColor: 'rgba(10,25,18,0.9)',
                            borderColor: 'rgba(201,169,110,0.3)',
                            borderWidth: 1,
                        }
                    }
                }
            });
        }

        // ─── Fetch data from API ───
        function loadData(period) {
            // show all loaders
            ['loadingTrend','loadingDist','loadingFoh','loadingFnb','loadingVolume','loadingGuest','loadingPurpose']
                .forEach(showLoading);

            fetch('api/analytics_data.php?period=' + encodeURIComponent(period))
                .then(function(res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(function(data) {
                    if (data.error) { console.error(data.error); return; }
                    buildCharts(data);
                })
                .catch(function(err) {
                    console.error('Failed to load analytics:', err);
                    ['loadingTrend','loadingDist','loadingFoh','loadingFnb','loadingVolume','loadingGuest','loadingPurpose']
                        .forEach(hideLoading);
                });
        }

        // ─── Period filter buttons ───
        document.getElementById('periodFilters').addEventListener('click', function(e) {
            var btn = e.target.closest('.period-btn');
            if (!btn) return;
            // Update active state
            document.querySelectorAll('.period-btn').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            // Load data
            loadData(btn.dataset.period);
        });

        // ─── Initial load ───
        loadData('all');
    })();
    </script>
</body>
</html>
