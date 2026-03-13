<?php
/**
 * ═══════════════════════════════════════════════════════════════
 * ADMIN PRINTABLE REPORTS
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
    <title>Reports - Admin - John Hay Hotels</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        .lodge-input {
            padding: 10px 14px; border-radius: 10px;
            border: 1px solid rgba(201,169,110,0.2);
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85);
            font-family: 'Inter', sans-serif; font-size: 0.8rem;
            transition: all 0.3s ease; outline: none;
        }
        .lodge-input:focus { border-color: #C9A96E; background: rgba(255,255,255,0.1); }
        .lodge-input::placeholder { color: rgba(255,255,255,0.3); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: rgba(10,25,18,0.5); }
        ::-webkit-scrollbar-thumb { background: rgba(201,169,110,0.3); border-radius: 99px; }
        .preset-btn {
            padding: 10px 20px; border-radius: 10px; font-size: 0.75rem;
            font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em;
            border: 1px solid rgba(201,169,110,0.15); color: rgba(255,255,255,0.4);
            background: transparent; cursor: pointer; transition: all 0.3s ease;
        }
        .preset-btn:hover { border-color: rgba(201,169,110,0.4); color: rgba(255,255,255,0.7); }
        .preset-btn.active {
            background: linear-gradient(135deg, #C9A96E, #b5893a);
            color: #0A1912; border-color: transparent;
        }
        @keyframes fadeUp { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
        .fade-up { opacity: 0; animation: fadeUp 0.5s ease-out forwards; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { width: 32px; height: 32px; border: 3px solid rgba(201,169,110,0.2);
            border-top-color: #C9A96E; border-radius: 50%; animation: spin 0.8s linear infinite; }
        
        .nav-link { padding: 8px 16px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; transition: all 0.3s ease; color: rgba(255,255,255,0.7); }
        .nav-link:hover { background: rgba(201,169,110,0.1); color: #ffffff; }
        .nav-link.active { color: #C9A96E; }
        
        /* Hover transitions matching the official website */
        nav.group:hover .nav-link { color: #1B3A2D; opacity: 0.7; }
        nav.group:hover .nav-link:hover { background: rgba(201,169,110,0.1); opacity: 1; color: #1B3A2D; }
        nav.group:hover .nav-link.active { background: rgba(201,169,110,0.15); opacity: 1; color: #b5893a; }
        
        .logo-img { filter: brightness(0) invert(1); transition: all 0.3s ease; }
        nav.group:hover .logo-img { filter: none; }

        /* ═══ PRINT STYLES ═══ */
        @media print {
            * { color: #000 !important; background: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            body { background: #fff !important; font-size: 10pt; }
            nav, .no-print, footer, #controlsPanel { display: none !important; }
            .glass-card { border: 1px solid #ddd !important; backdrop-filter: none !important; box-shadow: none !important; }
            #reportContent { display: block !important; }
            .print-header { display: block !important; text-align: center; margin-bottom: 30px; border-bottom: none; }
            .print-header img { height: 80px; margin: 0 auto 20px auto; display: block; }
            .print-header h1 { font-family: 'Inter', sans-serif; font-size: 16pt; color: #1B3A2D !important; margin: 0 0 10px 0; text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; }
            .print-header p { font-size: 10pt; color: #666 !important; margin: 4px 0; }
            .report-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
            .report-table th { background: #1B3A2D !important; color: #fff !important; padding: 8px 12px; text-align: left; font-size: 9pt; text-transform: uppercase; letter-spacing: 0.05em; }
            .report-table td { padding: 6px 12px; border-bottom: 1px solid #eee; font-size: pt; }
            .report-table tr:nth-child(even) td { background: #f9f9f9 !important; }
            .report-section { page-break-inside: avoid; margin-bottom: 20px; }
            .report-section h3 { font-family: 'Playfair Display', serif; font-size: 13pt; color: #1B3A2D !important; border-bottom: 2px solid #C9A96E; padding-bottom: 8px; margin-bottom: 12px; text-align: left; font-weight: 700; }
            .stat-box { display: inline-block; width: 23%; text-align: center; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin: 0 0.5%; }
            .stat-box .stat-val { font-size: 20pt; font-weight: 700; color: #1B3A2D !important; }
            .stat-box .stat-label { font-size: 7pt; text-transform: uppercase; letter-spacing: 0.1em; color: #999 !important; }
            .score-excellent { color: #059669 !important; font-weight: 600; }
            .score-good { color: #16a34a !important; font-weight: 600; }
            .score-poor { color: #dc2626 !important; font-weight: 600; }
            .comment-card { border: 1px solid #eee; padding: 8px 12px; margin-bottom: 8px; border-radius: 4px; page-break-inside: avoid; }
            .comment-card .guest-info { font-size: 8pt; color: #999 !important; }
            .comment-card .comment-text { font-size: 9pt; font-style: italic; color: #333 !important; margin-top: 4px; }
            .comment-page { display: block !important; }
            .print-footer { text-align: center; font-size: 7pt; color: #aaa !important; margin-top: 30px; padding-top: 10px; border-top: 1px solid #eee; }
            .print-only-table { display: block !important; margin-top: 10px; }
            #reportModal { display: none !important; }
        }

        /* ═══ SCREEN-ONLY REPORT STYLES ═══ */
        @media screen {
            .print-header { display: none; }
            .report-table { width: 100%; border-collapse: separate; border-spacing: 0; }
            .report-table th { background: rgba(201,169,110,0.1); color: rgba(201,169,110,0.8); padding: 10px 14px; text-align: left; font-size: 1rem; text-transform: uppercase; letter-spacing: 0.15em; font-weight: 700; border-bottom: 1px solid rgba(201,169,110,0.15); }
            .report-table td { padding: 8px 14px; border-bottom: 1px solid rgba(255,255,255,0.04); color: rgba(255,255,255,0.6); font-size: 1rem; }
            .report-table tr:hover td { background: rgba(255,255,255,0.02); }
            .report-section h3 { font-family: 'Playfair Display', serif; color: #C9A96E; font-size: 1.3rem; letter-spacing: 0.15em; text-transform: uppercase; font-weight: 600;
                border-bottom: 2px solid #C9A96E; border-image: linear-gradient(to right, rgba(201,169,110,1), rgba(201,169,110,0)) 1; padding-bottom: 12px; margin-bottom: 20px; text-align: left; text-shadow: 0 0 10px rgba(201,169,110,0.2); }
            .stat-box { text-align: center; padding: 16px; background: rgba(245,235,224,0.06); border: 1px solid rgba(201,169,110,0.12); border-radius: 12px; }
            .stat-box .stat-val { font-size: 1.8rem; font-weight: 700; color: rgba(255,255,255,0.85); }
            .stat-box .stat-label { font-size: 0.55rem; text-transform: uppercase; letter-spacing: 0.15em; color: rgba(201,169,110,0.6); font-weight: 600; }
            .score-excellent { color: #10b981; font-weight: 600; }
            .score-good { color: #22c55e; font-weight: 600; }
            .score-poor { color: #ef4444; font-weight: 600; }
            
            .comment-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06); padding: 12px 16px; margin-bottom: 8px; border-radius: 10px; }
            .comment-card .guest-info { font-size: 0.65rem; color: rgba(201,169,110,0.5); }
            .comment-card .comment-text { font-size: 1rem; font-style: italic; color: rgba(255,255,255,0.5); margin-top: 4px; }
            .print-footer { display: none; }
            .print-only-table { display: none; }
            
            /* Modal Styles */
            .modal-backdrop { position: fixed; inset: 0; background: rgba(10,25,18,0.85); backdrop-filter: blur(5px); z-index: 9999; display: flex; justify-content: center; align-items: flex-start; opacity: 0; pointer-events: none; transition: all 0.3s ease; padding: 3rem 1rem; overflow-y: auto; }
            .modal-backdrop.active { opacity: 1; pointer-events: auto; }
            .modal-container { background: #0A1912; border: 1px solid rgba(201,169,110,0.2); border-radius: 12px; width: 100%; max-width: 800px; transform: translateY(-20px) scale(0.95); transition: all 0.3s ease; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7); display: flex; flex-direction: column; }
            .modal-backdrop.active .modal-container { transform: translateY(0) scale(1); }
            .modal-header { padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: space-between; align-items: center; }
            .modal-body { padding: 24px; }
        }
    </style>
</head>
<body class="font-sans text-white min-h-screen">

    <!-- ═══ TOP NAV BAR ═══ -->
    <nav class="group bg-transparent hover:bg-white px-6 py-3 no-print relative z-10 border-b border-white/[0.06] hover:border-gold-400/20 transition-all duration-300 ease-in-out hover:shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <img src="../img/logo.png" alt="John Hay Hotels Logo" class="logo-img h-16 sm:h-20 w-auto object-contain">
                <span class="text-[0.8rem] font-bold text-white/70 group-hover:text-pine-900 uppercase tracking-[0.2em] px-3 py-1 rounded-full bg-white/5 group-hover:bg-gold-400/20 border border-white/10 group-hover:border-gold-400/30 hidden sm:inline-block transition-colors duration-300">Admin Panel</span>
            </div>
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="index.php" class="nav-link">Dashboard</a>
                <a href="analytics.php" class="nav-link">Analytics</a>
                <a href="reports.php" class="nav-link active">Reports</a>
                <span class="text-white/20 group-hover:text-pine-900/20 mx-1 sm:mx-2 transition-colors duration-300">|</span>
                <a href="logout.php" class="text-[0.9rem] font-semibold text-white/50 hover:text-red-400 group-hover:text-red-600/80 group-hover:hover:text-red-700 group-hover:hover:bg-red-50 transition-colors flex items-center gap-1.5 px-3 py-2 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="hidden sm:inline">Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- ═══ CONTROLS PANEL ═══ -->
      <div class="glass-card rounded-xl p-5 mb-6 fade-up" style="animation-delay:0.1s" id="controlsPanel">
        <div class="flex flex-wrap items-end justify-between gap-6">
            
            <!-- Left Side: Inputs -->
            <div class="flex flex-wrap items-end gap-4">
                <!-- 1. Quick Presets -->
                <div class="flex flex-col" style="min-width: 150px;">
                    <label class="block text-[0.7rem] font-bold text-gold-400/90 uppercase tracking-[0.1em] mb-2 whitespace-nowrap">Quick Presets</label>
                    <div class="relative w-full">
                        <select id="presetSelect" class="lodge-input w-full px-3 py-2 text-sm transition-colors cursor-pointer" style="appearance: none; padding-right: 2.5rem;">
                            <option value="today" style="background: #0A1912; color: inherit;">Daily (Today)</option>
                            <option value="yesterday" style="background: #0A1912; color: inherit;">Yesterday</option>
                            <option value="week" style="background: #0A1912; color: inherit;">Weekly (7 Days)</option>
                            <option value="month" style="background: #0A1912; color: inherit;">Monthly (30 Days)</option>
                            <option value="custom" hidden style="background: #0A1912; color: inherit;">Custom Date</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- 2. Date From -->
                <div class="flex flex-col" style="min-width: 140px;">
                    <label class="block text-[0.7rem] font-bold text-gold-400/90 uppercase tracking-[0.1em] mb-2 whitespace-nowrap">From</label>
                    <input type="date" id="dateFrom" class="lodge-input w-full px-3 py-2 text-sm transition-colors">
                </div>

                <!-- 3. Date To -->
                <div class="flex flex-col" style="min-width: 140px;">
                    <label class="block text-[0.7rem] font-bold text-gold-400/90 uppercase tracking-[0.1em] mb-2 whitespace-nowrap">To</label>
                    <input type="date" id="dateTo" class="lodge-input w-full px-3 py-2 text-sm transition-colors">
                </div>
            </div>

            <!-- Right Side: Buttons -->
            <div class="flex flex-wrap items-end gap-3 mt-2 lg:mt-0 lg:ml-auto">
                <!-- 4. Generate Button -->
                <button id="btnGenerate" class="px-5 py-2 rounded-lg font-bold text-[0.8rem] uppercase tracking-wider transition-all hover:scale-[1.02] active:scale-95" style="background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912;box-shadow:0 4px 15px rgba(201,169,110,0.2);">
                    Generate Report
                </button>

                <!-- 5. Print Button -->
                <button id="btnPrint" class="px-5 py-2 rounded-lg font-bold text-[0.8rem] uppercase tracking-wider border border-gold-400/30 text-gold-400/90 hover:text-gold-400 hover:border-gold-400/60 hover:bg-gold-400/10 transition-all flex items-center gap-2" style="visibility:hidden; opacity:0; pointer-events:none;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Report
                </button>
            </div>
            
        </div>
    </div>
        <!-- ═══ LOADING ═══ -->
        <div id="loadingReport" class="no-print text-center py-16" style="display:none">
            <div class="spinner mx-auto mb-4"></div>
            <p class="text-white/30 text-[1.125rem]">Generating report...</p>
        </div>

        <!-- ═══ EMPTY STATE ═══ -->
        <div id="emptyState" class="no-print glass-card rounded-xl p-12 text-center fade-up" style="animation-delay:0.2s">
            <div class="w-20 h-20 rounded-full bg-gold-400/10 flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gold-400/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-serif text-[1.5rem] text-white/60 mb-2">Select a Report Period</h3>
            <p class="text-white/30 text-[1.125rem] max-w-md mx-auto">Choose a quick preset or specify a custom date range above, then click "Generate Report" to view the feedback summary.</p>
        </div>

        <!-- ═══ REPORT CONTENT (Generated via JS) ═══ -->
        <div id="reportContent" style="display:none"></div>

    </div>

    <!-- Footer -->
    <footer class="text-center py-6 border-t border-white/[0.04] mt-8 no-print">
        <p class="text-gold-400/100 text-[0.8rem] uppercase tracking-[0.3em]">John Hay Hotels - Forest Wing Admin Panel</p>
    </footer>

    <!-- ═══ DATA MODAL ═══ -->
    <div id="reportModal" class="modal-backdrop no-print">
        <div class="modal-container text-white">
            <div class="modal-header">
                <h3 id="modalTitle" class="font-serif text-[1.4rem] text-gold-400 tracking-wider">Details</h3>
                <button type="button" id="closeModalBtn" class="text-white/40 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div id="modalContent" class="modal-body overflow-x-auto">
                <!-- Data injected here -->
            </div>
        </div>
    </div>

    <script>
    (function() {
        var dateFrom = document.getElementById('dateFrom');
        var dateTo = document.getElementById('dateTo');
        var btnGenerate = document.getElementById('btnGenerate');
        var btnPrint = document.getElementById('btnPrint');
        var reportContent = document.getElementById('reportContent');
        var loadingEl = document.getElementById('loadingReport');
        var emptyState = document.getElementById('emptyState');

        // ─── Helper: format date YYYY-MM-DD ───
        function formatDate(d) {
            var year = d.getFullYear();
            var month = String(d.getMonth() + 1).padStart(2, '0');
            var day = String(d.getDate()).padStart(2, '0');
            return year + '-' + month + '-' + day;
        }

        // ─── Helper: format date for display ───
        function displayDate(dateStr) {
            if (!dateStr) return '—';
            var d = new Date(dateStr + 'T00:00:00');
            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            return months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear();
        }

        // ─── Helper: score class ───
        function scoreClass(val, max) {
            if (val >= 4) return 'score-excellent';
            if (val >= 3) return 'score-good';
            return 'score-poor';
        }

        // ─── Helper: score label ───
        function scoreLabel(val) {
            if (val >= 4) return 'Excellent';
            if (val >= 3) return 'Good';
            if (val > 0) return 'Poor';
            return 'N/A';
        }

        // ─── Set date preset ───
        function setPreset(type) {
            var today = new Date();
            var from, to;
            switch (type) {
                case 'today':
                    from = to = formatDate(today);
                    break;
                case 'yesterday':
                    var yd = new Date(today);
                    yd.setDate(yd.getDate() - 1);
                    from = to = formatDate(yd);
                    break;
                case 'week':
                    to = formatDate(today);
                    var w = new Date(today);
                    w.setDate(w.getDate() - 6);
                    from = formatDate(w);
                    break;
                case 'month':
                    to = formatDate(today);
                    var m = new Date(today);
                    m.setDate(m.getDate() - 29);
                    from = formatDate(m);
                    break;
            }
            dateFrom.value = from;
            dateTo.value = to;
        }

        // ─── Preset select ───
        document.getElementById('presetSelect').addEventListener('change', function(e) {
            var preset = e.target.value;
            if (preset === 'custom') return;
            setPreset(preset);
            generateReport(dateFrom.value, dateTo.value);
        });

        dateFrom.addEventListener('change', function() { document.getElementById('presetSelect').value = 'custom'; });
        dateTo.addEventListener('change', function() { document.getElementById('presetSelect').value = 'custom'; });

        // ─── Generate report ───
        btnGenerate.addEventListener('click', function() {
            if (!dateFrom.value || !dateTo.value) {
                alert('Please select both From and To dates.');
                return;
            }
            generateReport(dateFrom.value, dateTo.value);
        });

        // ─── Print button ───
        btnPrint.addEventListener('click', function() {
            window.print();
        });

        // ─── Build report HTML ───
        function generateReport(from, to) {
            emptyState.style.display = 'none';
            
            // Disable buttons to avoid multiple clicks
            btnGenerate.disabled = true;
            btnGenerate.style.opacity = '0.7';
            btnGenerate.innerText = 'Generating...';
            
            btnPrint.disabled = true;
            btnPrint.style.opacity = '0.5';
            btnPrint.style.pointerEvents = 'none';

            // Keep reportContent visible but dim it (if it already has content)
            if (reportContent.innerHTML.trim() !== '') {
                reportContent.style.opacity = '0.4';
                reportContent.style.pointerEvents = 'none';
                loadingEl.style.display = 'none'; // Don't show large spinner if we already have content
            } else {
                reportContent.style.display = 'none';
                loadingEl.style.display = 'block';
            }

            fetch('api/report_data.php?date_from=' + encodeURIComponent(from) + '&date_to=' + encodeURIComponent(to))
                .then(function(res) {
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    return res.json();
                })
                .then(function(data) {
                    if (data.error) { 
                        alert(data.error); 
                        resetLoadingState(); 
                        loadingEl.style.display = 'none';
                        if (reportContent.innerHTML.trim() === '') emptyState.style.display = 'block'; 
                        return; 
                    }
                    renderReport(data);
                    
                    loadingEl.style.display = 'none';
                    reportContent.style.display = 'block';
                    reportContent.style.opacity = '1';
                    reportContent.style.pointerEvents = 'auto';
                    
                    btnPrint.style.visibility = 'visible';
                    btnPrint.style.opacity = '1';
                    btnPrint.style.pointerEvents = 'auto';
                    resetLoadingState();
                })
                .catch(function(err) {
                    console.error(err);
                    resetLoadingState();
                    loadingEl.style.display = 'none';
                    if (reportContent.innerHTML.trim() === '') emptyState.style.display = 'block';
                    alert('Failed to generate report. Please try again.');
                });
        }
        
        function resetLoadingState() {
            btnGenerate.disabled = false;
            btnGenerate.style.opacity = '1';
            btnGenerate.innerText = 'Generate Report';
            
            btnPrint.disabled = false;
            btnPrint.style.opacity = '1';
            btnPrint.style.pointerEvents = 'auto';
        }

        function renderReport(data) {
            var s = data.summary;
            var now = new Date();
            var generatedAt = now.toLocaleDateString('en-US', {month:'long', day:'numeric', year:'numeric'}) + ' at ' +
                now.toLocaleTimeString('en-US', {hour:'2-digit', minute:'2-digit'});

            var html = '';

            // ── Print Header (visible only in print) ──
            html += '<div class="print-header">';
            html += '<img src="../img/logo.png" alt="John Hay Hotels Logo">';
            html += '<h1>Guest Feedback Summary Report</h1>';
            html += '<p>Report Generated on: ' + generatedAt + '</p>';
            html += '<p>Period: ' + displayDate(data.date_from) + ' — ' + displayDate(data.date_to) + '</p>';
            html += '</div>';

            // ── Screen title ──
            html += '<div class="no-print mb-6">';
            html += '<div class="flex items-center justify-between">';
            html += '<div>';
            html += '<h3 class="font-serif text-[1.5rem] text-white/80 tracking-wide">Report: ' + displayDate(data.date_from) + ' — ' + displayDate(data.date_to) + '</h3>';
            html += '</div></div></div>';
            
            // ── Modal Section Helper ──
            function buildModalSection(title, desc, tblHtml) {
                var sHtml = '<div class="report-section">';
                sHtml += '<h3>' + title + '</h3>';
                sHtml += '<div class="no-print stat-box" style="padding:16px 20px; text-align:left; background: rgba(245,235,224,0.03); display:flex; justify-content:space-between; align-items:center;">';
                sHtml += '<div><div style="font-size:1rem;color:rgba(255,255,255,0.85);font-weight:600;">Detailed Breakdown</div><div style="font-size:0.8rem;color:rgba(255,255,255,0.4); margin-top:2px;">' + desc + '</div></div>';
                sHtml += '<button type="button" class="btn-open-modal px-4 py-2 rounded bg-gold-400/10 hover:bg-gold-400/20 border border-gold-400/20 text-gold-400 text-[0.75rem] uppercase tracking-wider font-bold transition-transform active:scale-95" data-title="' + escapeHtml(title) + '" data-content="' + encodeURIComponent(tblHtml) + '">View Details</button>';
                sHtml += '</div>';
                sHtml += '<div class="print-only-table">' + tblHtml + '</div>';
                sHtml += '</div>';
                return sHtml;
            }

            // ── Summary Stats ──
            html += '<div class="report-section">';
            html += '<h3>Summary Overview</h3>';
            html += '<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">';
            html += '<div class="stat-box" style="flex:1;min-width:120px"><div class="stat-val">' + (s.total_responses || 0) + '</div><div class="stat-label">Total Responses</div></div>';
            html += '<div class="stat-box" style="flex:1;min-width:120px"><div class="stat-val">' + (s.avg_nps !== null ? s.avg_nps : '—') + '<span style="font-size:0.6em;opacity:0.4">/5</span></div><div class="stat-label">Avg. Satisfaction</div></div>';
            html += '<div class="stat-box" style="flex:1;min-width:120px"><div class="stat-val ' + (s.poor > 0 ? 'score-poor' : '') + '">' + (s.poor || 0) + '</div><div class="stat-label">Poor (1-2)</div></div>';
            html += '<div class="stat-box" style="flex:1;min-width:120px"><div class="stat-val ' + (s.good > 0 ? 'score-good' : '') + '">' + (s.good || 0) + '</div><div class="stat-label">Good (3)</div></div>';
            html += '<div class="stat-box" style="flex:1;min-width:120px"><div class="stat-val ' + (s.excellent > 0 ? 'score-excellent' : '') + '">' + (s.excellent || 0) + '</div><div class="stat-label">Excellent (4-5)</div></div>';
            html += '</div></div>';

            // ── NPS Distribution ──
            html += '<div class="report-section">';
            html += '<h3>Satisfaction Score Distribution</h3>';
            html += '<table class="report-table"><thead><tr>';
            html += '<th>Score</th>';
            for (var i = 1; i <= 5; i++) html += '<th style="text-align:center">' + i + '</th>';
            html += '<th style="text-align:center">Total</th></tr></thead><tbody><tr>';
            html += '<td><strong>Responses</strong></td>';
            var totalDist = 0;
            for (var i = 1; i <= 5; i++) {
                var val = data.nps_distribution[i] || 0;
                totalDist += val;
                html += '<td style="text-align:center">' + val + '</td>';
            }
            html += '<td style="text-align:center"><strong>' + totalDist + '</strong></td>';
            html += '</tr></tbody></table></div>';

            // ── Recognized Staff ──
            if (data.recognized_staff && data.recognized_staff.length > 0) {
                html += '<div class="report-section">';
                html += '<h3>⭐ Top Recognized Staff</h3>';
                html += '<div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:16px">';
                data.recognized_staff.forEach(function(staff) {
                    html += '<div class="stat-box" style="flex:1;min-width:140px; border-color: rgba(201,169,110,0.3); background: rgba(201,169,110,0.05); padding: 12px; display:flex; flex-direction:column; justify-content:center;">';
                    html += '<div class="stat-val" style="color: text-white/70; font-size: 1.25rem; margin-bottom: 2px;">' + escapeHtml(staff.name) + '</div>';
                    html += '<div class="stat-label" style="font-size: 0.5rem; text-transform: uppercase;">Mentioned ' + staff.count + ' time' + (staff.count > 1 ? 's' : '') + '</div>';
                    html += '</div>';
                });
                html += '</div></div>';
            }



            // ── Hotel Process & Associates ──
            var processTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Category</th><th style="text-align:center">Avg. Score</th><th style="text-align:center">Rating</th></tr></thead><tbody>';
            (data.hotel_process || []).forEach(function(item) {
                var cls = scoreClass(item.avg, 10);
                processTable += '<tr><td>' + item.label + '</td>';
                processTable += '<td style="text-align:center" class="' + cls + '">' + (item.avg > 0 ? item.avg.toFixed(1) + '/10' : 'N/A') + '</td>';
                processTable += '<td style="text-align:center" class="' + cls + '">' + scoreLabel(item.avg) + '</td></tr>';
            });
            processTable += '</tbody></table>';
            html += buildModalSection('Our Hotel Process & Associates', 'Analyze scores for reservations, check-in, check-out, and associate services.', processTable);

            // ── Forest Wing Hospitality ──
            var hospitalityTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Category</th><th style="text-align:center">Avg. Score</th><th style="text-align:center">Rating</th></tr></thead><tbody>';
            (data.forest_wing || []).forEach(function(item) {
                var cls = scoreClass(item.avg, 10);
                hospitalityTable += '<tr><td>' + item.label + '</td>';
                hospitalityTable += '<td style="text-align:center" class="' + cls + '">' + (item.avg > 0 ? item.avg.toFixed(1) + '/10' : 'N/A') + '</td>';
                hospitalityTable += '<td style="text-align:center" class="' + cls + '">' + scoreLabel(item.avg) + '</td></tr>';
            });
            hospitalityTable += '</tbody></table>';
            html += buildModalSection('Forest Wing Hospitality', 'Analyze scores for friendliness, attentiveness, and courteousness.', hospitalityTable);

            // ── Guestroom Ratings ──
            var guestroomTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Category</th><th style="text-align:center">Avg. Score</th><th style="text-align:center">Rating</th></tr></thead><tbody>';
            (data.guestroom || []).forEach(function(item) {
                var cls = scoreClass(item.avg, 10);
                guestroomTable += '<tr><td>' + item.label + '</td>';
                guestroomTable += '<td style="text-align:center" class="' + cls + '">' + (item.avg > 0 ? item.avg.toFixed(1) + '/10' : 'N/A') + '</td>';
                guestroomTable += '<td style="text-align:center" class="' + cls + '">' + scoreLabel(item.avg) + '</td></tr>';
            });
            guestroomTable += '</tbody></table>';
            html += buildModalSection('Our Guestroom', 'View guest ratings for cleanliness, ambiance, comfort, and bathroom.', guestroomTable);

            // ── Food & Beverage ──
            var fnbTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Category</th><th style="text-align:center">Avg. Score</th><th style="text-align:center">Rating</th></tr></thead><tbody>';
            (data.food_beverage || []).forEach(function(item) {
                var cls = scoreClass(item.avg, 10);
                fnbTable += '<tr><td>' + item.label + '</td>';
                fnbTable += '<td style="text-align:center" class="' + cls + '">' + (item.avg > 0 ? item.avg.toFixed(1) + '/10' : 'N/A') + '</td>';
                fnbTable += '<td style="text-align:center" class="' + cls + '">' + scoreLabel(item.avg) + '</td></tr>';
            });
            fnbTable += '</tbody></table>';
            html += buildModalSection('Food &amp; Beverage Ratings', 'View guest ratings for restaurant quality, room service, and bar experience.', fnbTable);

            // ── Purpose of Stay ──
            if (data.purpose_breakdown && data.purpose_breakdown.length > 0) {
                var purpTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Purpose</th><th style="text-align:center">Count</th><th style="text-align:center">Percentage</th></tr></thead><tbody>';
                var totalPurpose = 0;
                data.purpose_breakdown.forEach(function(p) { totalPurpose += parseInt(p.count); });
                data.purpose_breakdown.forEach(function(p) {
                    var pct = totalPurpose > 0 ? ((parseInt(p.count) / totalPurpose) * 100).toFixed(1) : 0;
                    purpTable += '<tr><td>' + p.purpose + '</td><td style="text-align:center">' + p.count + '</td><td style="text-align:center">' + pct + '%</td></tr>';
                });
                purpTable += '</tbody></table>';
                html += buildModalSection('Purpose of Stay', 'Explore why guests are visiting John Hay Hotels.', purpTable);
            }

            // ── Nationality Breakdown ──
            if (data.nationality_breakdown && data.nationality_breakdown.length > 0) {
                var natTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Nationality</th><th style="text-align:center">Count</th><th style="text-align:center">Percentage</th></tr></thead><tbody>';
                var totalNation = 0;
                data.nationality_breakdown.forEach(function(n) { totalNation += parseInt(n.count); });
                data.nationality_breakdown.forEach(function(n) {
                    var pct = totalNation > 0 ? ((parseInt(n.count) / totalNation) * 100).toFixed(1) : 0;
                    natTable += '<tr><td>' + n.nation + '</td><td style="text-align:center">' + n.count + '</td><td style="text-align:center">' + pct + '%</td></tr>';
                });
                natTable += '</tbody></table>';
                html += buildModalSection('Nationality Distribution', 'Analyze the demographic origins of the feedback respondents.', natTable);
            }

            // ── Guest Type ──
            if (data.first_stay && data.first_stay.length > 0) {
                var gstTable = '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Type</th><th style="text-align:center">Count</th><th></th></tr></thead><tbody>';
                data.first_stay.forEach(function(fs) {
                    gstTable += '<tr><td>' + fs.type + '</td><td style="text-align:center">' + fs.count + '</td><td></td></tr>';
                });
                gstTable += '</tbody></table>';
                html += buildModalSection('Guest Type', 'See the ratio of first-time vs returning guests.', gstTable);
            }

            // ── Daily Breakdown ──
            if (data.daily_breakdown && data.daily_breakdown.length > 0) {
                html += '<div class="report-section">';
                html += '<h3>Daily Breakdown</h3>';
                html += '<table class="report-table" style="table-layout:fixed;width:100%"><colgroup><col style="width:50%"><col style="width:25%"><col style="width:25%"></colgroup><thead><tr><th>Date</th><th style="text-align:center">Responses</th><th style="text-align:center">Avg. Satisfaction</th></tr></thead><tbody>';
                data.daily_breakdown.forEach(function(d) {
                    var cls = scoreClass(parseFloat(d.avg_rating), 10);
                    html += '<tr><td>' + displayDate(d.date) + '</td><td style="text-align:center">' + d.count + '</td>';
                    html += '<td style="text-align:center" class="' + cls + '">' + d.avg_rating + '/5</td></tr>';
                });
                html += '</tbody></table></div>';
            }

            // ── Guest Comments ──
            var validComments = [];
            (data.comments || []).forEach(function(c) {
                if (c.general_comments || c.helpful_staff_names) {
                    validComments.push(c);
                }
            });

            var totalPages = 0;
            if (validComments.length > 0) {
                html += '<div class="report-section">';
                html += '<h3>Guest Comments</h3>';
                
                var commentsPerPage = 5;
                totalPages = Math.ceil(validComments.length / commentsPerPage);
                
                html += '<div id="comments-wrapper">';
                for (var p = 1; p <= totalPages; p++) {
                    var displayStyle = p === 1 ? 'block' : 'none';
                    html += '<div class="comment-page" id="comment-page-' + p + '" style="display:' + displayStyle + ';">';
                    
                    var startIdx = (p - 1) * commentsPerPage;
                    var endIdx = Math.min(startIdx + commentsPerPage, validComments.length);
                    
                    for (var i = startIdx; i < endIdx; i++) {
                        var c = validComments[i];
                        var allComments = [];
                        if (c.general_comments) allComments.push('<strong>Comments & Suggestions:</strong><br>' + String(c.general_comments).replace(/\n/g, '<br>'));

                        html += '<div class="comment-card">';
                        html += '<div class="guest-info">' + (c.guest_name || 'Anonymous') + ' — Room ' + (c.room_no || '—') + ' — Rating: ' + (c.overall_rating || '—') + '/5 — ' + displayDate(c.created_at ? c.created_at.substring(0, 10) : '') + '</div>';
                        
                        if (c.helpful_staff_names) {
                            html += '<div style="margin-top: 8px; margin-bottom: 4px;"><span style="display:inline-block; background:rgba(201,169,110,0.15); color:#C9A96E; border:1px solid rgba(201,169,110,0.3); padding:4px 8px; border-radius:6px; font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em;">⭐ Recognized: <span style="color:#fff; text-transform:none; font-style:italic;">' + escapeHtml(c.helpful_staff_names) + '</span></span></div>';
                        }

                        allComments.forEach(function(cm) {
                            html += '<div class="comment-text">' + cm + '</div>';
                        });
                        html += '</div>';
                    }
                    html += '</div>';
                }
                html += '</div>';
                
                if (totalPages > 1) {
                    html += '<div class="no-print mt-4 flex items-center justify-between border-t border-white/5 pt-4">';
                    html += '<div class="text-white/40 text-[0.85rem]">Showing page <span id="comments-current-page" class="text-gold-400 font-bold">1</span> of ' + totalPages + '</div>';
                    html += '<div class="flex gap-2">';
                    html += '<button id="btn-prev-comments" class="px-3 py-1.5 rounded bg-white/5 hover:bg-white/10 text-white/70 text-[0.8rem] uppercase tracking-wider font-semibold disabled:opacity-30 disabled:pointer-events-none transition-colors" disabled>Previous</button>';
                    html += '<button id="btn-next-comments" class="px-3 py-1.5 rounded bg-gold-400/20 hover:bg-gold-400/30 text-gold-400 text-[0.8rem] uppercase tracking-wider font-semibold disabled:opacity-30 disabled:pointer-events-none transition-colors">Next</button>';
                    html += '</div>';
                    html += '</div>';
                }
                html += '</div>';
            }

            // ── Print Footer ──
            html += '<div class="print-footer">';
            html += 'John Hay Hotels — Forest Wing — Camp John Hay, Baguio City — Confidential Report — Generated: ' + generatedAt;
            html += '</div>';

            reportContent.innerHTML = html;
            
            // ── Bind Modal Events ──
            var modal = document.getElementById('reportModal');
            var modalTitle = document.getElementById('modalTitle');
            var modalContent = document.getElementById('modalContent');
            var closeBtn = document.getElementById('closeModalBtn');
            
            function openModal(title, content) {
                modalTitle.innerHTML = title;
                modalContent.innerHTML = content;
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal() {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            closeBtn.onclick = closeModal;
            modal.onclick = function(e) {
                if (e.target === modal) closeModal();
            };
            
            var openBtns = document.querySelectorAll('.btn-open-modal');
            openBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    openModal(this.getAttribute('data-title'), decodeURIComponent(this.getAttribute('data-content')));
                });
            });

            // ── Comments Pagination Logic ──
            if (typeof totalPages !== 'undefined' && totalPages > 1) {
                var currentPage = 1;
                var btnPrev = document.getElementById('btn-prev-comments');
                var btnNext = document.getElementById('btn-next-comments');
                var pageSpan = document.getElementById('comments-current-page');
                
                function updatePagination() {
                    for (var p = 1; p <= totalPages; p++) {
                        var pageDiv = document.getElementById('comment-page-' + p);
                        if (pageDiv) {
                            pageDiv.style.display = (p === currentPage) ? 'block' : 'none';
                        }
                    }
                    if (pageSpan) pageSpan.innerText = currentPage;
                    if (btnPrev) btnPrev.disabled = (currentPage === 1);
                    if (btnNext) btnNext.disabled = (currentPage === totalPages);
                }
                
                if (btnPrev && btnNext) {
                    btnPrev.addEventListener('click', function() {
                        if (currentPage > 1) { currentPage--; updatePagination(); }
                    });
                    btnNext.addEventListener('click', function() {
                        if (currentPage < totalPages) { currentPage++; updatePagination(); }
                    });
                }
            }
        }

        // ─── HTML escape helper ───
        function escapeHtml(str) {
            var div = document.createElement('div');
            div.appendChild(document.createTextNode(str));
            return div.innerHTML;
        }

        // ─── Auto-load Daily Report on init ───
        document.addEventListener("DOMContentLoaded", function() {
            var presetSelect = document.getElementById('presetSelect');
            if (presetSelect) {
                presetSelect.value = 'today';
                setPreset('today');
                generateReport(dateFrom.value, dateTo.value);
            }
        });

    })();
    </script>
</body>
</html>
