<?php
session_start();
require_once "../config.php";
if (
    !isset($_SESSION["superadmin_logged_in"]) ||
    $_SESSION["superadmin_logged_in"] !== true
) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        >
        <title>Super Admin - Manage Admins</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link
            href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        >
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            pine: {
                                700: '#1e5832',
                                800: '#1B3A2D',
                                900: '#142B21',
                                950: '#0A1912'
                            },
                            gold: {
                                400: '#C9A96E',
                                500: '#b5893a'
                            }
                        },
                        fontFamily: {
                            script: ['"Great Vibes"', 'cursive'],
                            serif: ['"Playfair Display"', 'serif'],
                            sans: ['"Inter"', 'sans-serif']
                        }
                    }
                }
            }
        </script>
        <style>
            body {
                background: #0A1912
            }

            .glass-card {
                background: rgba(245, 235, 224, 0.06);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(201, 169, 110, 0.12)
            }

            .lodge-input {
                width: 100%;
                padding: 12px 16px;
                border-radius: 10px;
                border: 1px solid rgba(201, 169, 110, 0.2);
                background: rgba(255, 255, 255, 0.06);
                color: rgba(255, 255, 255, 0.85);
                font-family: 'Inter', sans-serif;
                font-size: 0.85rem;
                transition: all .3s ease;
                outline: none
            }

            .lodge-input::placeholder {
                color: rgba(255, 255, 255, 0.3)
            }

            .lodge-input:focus {
                border-color: #C9A96E;
                background: rgba(255, 255, 255, 0.1);
                box-shadow: 0 0 0 3px rgba(201, 169, 110, 0.1)
            }

            .nav-link {
                padding: 6px 14px;
                border-radius: 8px;
                font-size: .75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .1em;
                transition: all .3s ease
            }

            .nav-link:hover {
                background: rgba(201, 169, 110, 0.08);
                color: rgba(255, 255, 255, 0.7)
            }

            .nav-link.active {
                background: rgba(201, 169, 110, 0.12);
                color: #C9A96E
            }

            @keyframes fadeUp {
                0% {
                    opacity: 0;
                    transform: translateY(20px)
                }

                100% {
                    opacity: 1;
                    transform: translateY(0)
                }
            }

            .fade-up {
                opacity: 0;
                animation: fadeUp .5s ease-out forwards
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg)
                }
            }

            .modal-backdrop {
                position: fixed;
                inset: 0;
                z-index: 50;
                background: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                display: none;
                align-items: center;
                justify-content: center
            }

            .modal-backdrop.show {
                display: flex
            }

            .modal-content {
                background: rgba(20, 43, 33, 0.95);
                backdrop-filter: blur(24px);
                border: 1px solid rgba(201, 169, 110, 0.2);
                border-radius: 20px;
                box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
                width: 100%;
                max-width: 460px;
                padding: 32px
            }

            .badge-active {
                background: rgba(16, 185, 129, 0.15);
                color: #10b981;
                border: 1px solid rgba(16, 185, 129, 0.3)
            }

            .badge-inactive {
                background: rgba(239, 68, 68, 0.15);
                color: #ef4444;
                border: 1px solid rgba(239, 68, 68, 0.3)
            }

            .btn-action {
                padding: 6px 12px;
                border-radius: 8px;
                font-size: .7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .05em;
                cursor: pointer;
                transition: all .3s ease;
                border: 1px solid transparent
            }

            .btn-activate {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
                border-color: rgba(16, 185, 129, 0.3)
            }

            .btn-activate:hover {
                background: rgba(16, 185, 129, 0.2)
            }

            .btn-deactivate {
                background: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                border-color: rgba(239, 68, 68, 0.3)
            }

            .btn-deactivate:hover {
                background: rgba(239, 68, 68, 0.2)
            }

            .btn-reset {
                background: rgba(201, 169, 110, 0.1);
                color: #C9A96E;
                border-color: rgba(201, 169, 110, 0.3)
            }

            .btn-reset:hover {
                background: rgba(201, 169, 110, 0.2)
            }

            .toast {
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 100;
                padding: 14px 24px;
                border-radius: 12px;
                font-size: .85rem;
                font-weight: 500;
                transform: translateY(100px);
                opacity: 0;
                transition: all .4s ease
            }

            .toast.show {
                transform: translateY(0);
                opacity: 1
            }

            .toast-success {
                background: rgba(16, 185, 129, 0.9);
                color: #fff
            }

            .toast-error {
                background: rgba(239, 68, 68, 0.9);
                color: #fff
            }
        </style>
    </head>

    <body class="font-sans text-white min-h-screen">
        <nav class="border-b border-white/[0.06] px-6 py-4">
            <div class="max-w-6xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h1 class="font-script text-3xl text-white/70">John Hay
                        Hotels</h1><span
                        class="text-[0.55rem] font-bold text-gold-400/50 uppercase tracking-[0.2em] px-3 py-1 rounded-full border border-gold-400/20 flex items-center gap-1.5"
                    ><svg
                            class="w-3 h-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            />
                        </svg>Super Admin</span>
                </div>
                <div class="flex items-center gap-2"><a
                        href="index.php"
                        class="nav-link active"
                    >Manage Admins</a><span
                        class="text-white/10 mx-2">|</span><span
                        class="text-white/30 text-sm"
                    >Welcome, <span class="text-gold-400/70"><?= htmlspecialchars(
    $_SESSION["superadmin_username"] ?? "Super Admin",
) ?></span></span><a
                        href="logout.php"
                        class="text-sm text-white/30 hover:text-red-400/70 transition-colors flex items-center gap-1.5"
                    ><svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>Logout</a></div>
            </div>
        </nav>
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div
                class="flex flex-wrap items-center justify-between gap-4 mb-8 fade-up">
                <div>
                    <h2 class="font-serif text-2xl text-white/80 tracking-wide">
                        Admin Account Management</h2>
                    <p class="text-white/30 text-sm mt-1">Create, activate, and
                        deactivate administrator accounts</p>
                </div><button
                    onclick="openCreateModal()"
                    class="px-6 py-3 rounded-xl font-semibold text-sm uppercase tracking-wider flex items-center gap-2 transition-all duration-300 hover:shadow-lg hover:scale-[1.02]"
                    style="background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912"
                ><svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                        />
                    </svg>Create New Admin</button>
            </div>
            <div
                class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8 fade-up"
                style="animation-delay:0.1s"
            >
                <div class="glass-card rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-gold-400/10 flex items-center justify-center">
                            <svg
                                class="w-5 h-5 text-gold-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                        </div><span
                            class="text-[0.65rem] font-semibold text-gold-400/60 uppercase tracking-[0.15em]"
                        >Total Admins</span>
                    </div>
                    <p
                        class="text-3xl font-bold text-white/80"
                        id="statTotal"
                    >-</p>
                </div>
                <div class="glass-card rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-emerald-400/10 flex items-center justify-center">
                            <svg
                                class="w-5 h-5 text-emerald-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div><span
                            class="text-[0.65rem] font-semibold text-emerald-400/60 uppercase tracking-[0.15em]"
                        >Active</span>
                    </div>
                    <p
                        class="text-3xl font-bold text-emerald-400/80"
                        id="statActive"
                    >-</p>
                </div>
                <div class="glass-card rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-3">
                        <div
                            class="w-10 h-10 rounded-lg bg-red-400/10 flex items-center justify-center">
                            <svg
                                class="w-5 h-5 text-red-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"
                                />
                            </svg>
                        </div><span
                            class="text-[0.65rem] font-semibold text-red-400/60 uppercase tracking-[0.15em]"
                        >Deactivated</span>
                    </div>
                    <p
                        class="text-3xl font-bold text-red-400/80"
                        id="statInactive"
                    >-</p>
                </div>
            </div>
            <div
                class="glass-card rounded-xl overflow-hidden fade-up"
                style="animation-delay:0.2s"
            >
                <div
                    class="px-5 py-4 border-b border-white/[0.06] flex items-center justify-between">
                    <h3
                        class="font-serif text-white/70 text-base tracking-wider uppercase">
                        Administrator Accounts</h3><button
                        onclick="loadAdmins()"
                        class="text-white/30 hover:text-gold-400 transition-colors text-xs font-semibold uppercase tracking-wider flex items-center gap-1.5"
                    ><svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                            />
                        </svg>Refresh</button>
                </div>
                <div id="adminTableContainer">
                    <div class="px-6 py-16 text-center">
                        <div
                            class="w-8 h-8 rounded-full mx-auto mb-3"
                            style="animation:spin .8s linear infinite;border:3px solid rgba(201,169,110,0.2);border-top-color:#C9A96E"
                        ></div>
                        <p class="text-white/25 text-sm">Loading admin
                            accounts...</p>
                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center py-6 border-t border-white/[0.04] mt-8">
            <p class="text-white/15 text-[0.55rem] uppercase tracking-[0.3em]">
                John Hay Hotels - Forest Wing Super Admin Panel</p>
        </footer>
        <div
            class="modal-backdrop"
            id="createModal"
        >
            <div class="modal-content">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center">
                            <svg
                                class="w-5 h-5 text-gold-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"
                                />
                            </svg>
                        </div>
                        <h3
                            class="font-serif text-xl text-white/80 tracking-wide">
                            Create New Admin</h3>
                    </div><button
                        onclick="closeCreateModal()"
                        class="text-white/30 hover:text-white/60"
                    ><svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg></button>
                </div>
                <form
                    id="createAdminForm"
                    class="space-y-4"
                >
                    <div><label
                            class="block text-[0.65rem] font-semibold text-gold-400/70 uppercase tracking-[0.15em] mb-2"
                        >Full Name</label><input
                            type="text"
                            id="newFullName"
                            placeholder="e.g. Front Desk Manager"
                            class="lodge-input"
                        ></div>
                    <div><label
                            class="block text-[0.65rem] font-semibold text-gold-400/70 uppercase tracking-[0.15em] mb-2"
                        >Username *</label><input
                            type="text"
                            id="newUsername"
                            required
                            placeholder="e.g. frontdesk"
                            class="lodge-input"
                        ></div>
                    <div><label
                            class="block text-[0.65rem] font-semibold text-gold-400/70 uppercase tracking-[0.15em] mb-2"
                        >Password *</label><input
                            type="password"
                            id="newPassword"
                            required
                            placeholder="Min. 6 characters"
                            class="lodge-input"
                        ></div>
                    <div class="pt-3 flex gap-3"><button
                            type="button"
                            onclick="closeCreateModal()"
                            class="flex-1 py-3 rounded-full font-semibold text-sm uppercase tracking-wider border border-white/10 text-white/40 hover:text-white/60 hover:border-white/20 transition-all"
                        >Cancel</button><button
                            type="submit"
                            class="flex-1 py-3 rounded-full font-semibold text-sm uppercase tracking-wider transition-all duration-300 hover:shadow-lg"
                            style="background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912"
                        >Create Admin</button></div>
                </form>
            </div>
        </div>
        <div
            class="modal-backdrop"
            id="resetModal"
        >
            <div class="modal-content">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-gold-400/10 flex items-center justify-center">
                            <svg
                                class="w-5 h-5 text-gold-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3
                                class="font-serif text-xl text-white/80 tracking-wide">
                                Reset Password</h3>
                            <p
                                class="text-white/30 text-xs mt-0.5"
                                id="resetAdminLabel"
                            ></p>
                        </div>
                    </div><button
                        onclick="closeResetModal()"
                        class="text-white/30 hover:text-white/60"
                    ><svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg></button>
                </div>
                <form
                    id="resetPasswordForm"
                    class="space-y-4"
                ><input
                        type="hidden"
                        id="resetAdminId"
                    >
                    <div><label
                            class="block text-[0.65rem] font-semibold text-gold-400/70 uppercase tracking-[0.15em] mb-2"
                        >New Password *</label><input
                            type="password"
                            id="resetNewPassword"
                            required
                            placeholder="Min. 6 characters"
                            class="lodge-input"
                        ></div>
                    <div class="pt-3 flex gap-3"><button
                            type="button"
                            onclick="closeResetModal()"
                            class="flex-1 py-3 rounded-full font-semibold text-sm uppercase tracking-wider border border-white/10 text-white/40 hover:text-white/60 hover:border-white/20 transition-all"
                        >Cancel</button><button
                            type="submit"
                            class="flex-1 py-3 rounded-full font-semibold text-sm uppercase tracking-wider transition-all duration-300 hover:shadow-lg"
                            style="background:linear-gradient(135deg,#C9A96E,#b5893a);color:#0A1912"
                        >Reset Password</button></div>
                </form>
            </div>
        </div>
        <div
            class="toast"
            id="toast"
        ></div>
        <script>
            (function() {
                var c = document.getElementById('adminTableContainer');

                function t(m, y) {
                    var e = document.getElementById('toast');
                    e.textContent = m;
                    e.className = 'toast toast-' + (y || 'success') +
                        ' show';
                    setTimeout(function() {
                        e.classList.remove('show')
                    }, 3500)
                }
                window.loadAdmins = function() {
                    fetch('api/admins.php').then(function(r) {
                        return r.json()
                    }).then(function(d) {
                        if (d.error) {
                            t(d.error, 'error');
                            return
                        }
                        rA(d.admins || [])
                    }).catch(function() {
                        t('Failed to load admins.', 'error')
                    })
                };

                function rA(a) {
                    var ac = 0,
                        ic = 0;
                    a.forEach(function(x) {
                        x.is_active == 1 ? ac++ : ic++
                    });
                    document.getElementById('statTotal').textContent = a
                        .length;
                    document.getElementById('statActive').textContent = ac;
                    document.getElementById('statInactive').textContent =
                        ic;
                    if (a.length === 0) {
                        c.innerHTML =
                            '<div class="px-6 py-16 text-center"><p class="text-white/25 text-sm">No admin accounts found.</p></div>';
                        return
                    }
                    var h =
                        '<div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="border-b border-white/[0.06]"><th class="px-5 py-3.5 text-left text-[0.6rem] font-bold text-gold-400/60 uppercase tracking-[0.15em]">Admin</th><th class="px-5 py-3.5 text-left text-[0.6rem] font-bold text-gold-400/60 uppercase tracking-[0.15em]">Username</th><th class="px-5 py-3.5 text-center text-[0.6rem] font-bold text-gold-400/60 uppercase tracking-[0.15em]">Status</th><th class="px-5 py-3.5 text-center text-[0.6rem] font-bold text-gold-400/60 uppercase tracking-[0.15em]">Created</th><th class="px-5 py-3.5 text-center text-[0.6rem] font-bold text-gold-400/60 uppercase tracking-[0.15em]">Actions</th></tr></thead><tbody>';
                    a.forEach(function(x) {
                        var act = x.is_active == 1;
                        var bc = act ? 'badge-active' :
                            'badge-inactive';
                        var bt = act ? 'Active' : 'Deactivated';
                        var tbc = act ? 'btn-deactivate' :
                            'btn-activate';
                        var tbt = act ? 'Deactivate' : 'Activate';
                        var cr = x.created_at ? new Date(x
                            .created_at).toLocaleDateString(
                            'en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            }) : 'N/A';
                        h += '<tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition-colors"><td class="px-5 py-4"><div class="flex items-center gap-3"><div class="w-9 h-9 rounded-lg flex items-center justify-center text-xs font-bold uppercase" style="background:rgba(201,169,110,0.12);color:#C9A96E">' +
                            (x.full_name || x.username).charAt(0) +
                            '</div><span class="text-white/70 font-medium">' +
                            esc(x.full_name || '-') +
                            '</span></div></td><td class="px-5 py-4"><span class="text-white/50 font-mono text-xs bg-white/[0.04] px-2.5 py-1 rounded-md">' +
                            esc(x.username) +
                            '</span></td><td class="px-5 py-4 text-center"><span class="inline-block px-3 py-1 rounded-full text-[0.65rem] font-semibold uppercase tracking-wider ' +
                            bc + '">' + bt +
                            '</span></td><td class="px-5 py-4 text-center text-white/35 text-xs">' +
                            cr +
                            '</td><td class="px-5 py-4 text-center"><div class="flex items-center justify-center gap-2"><button onclick="toggleAdmin(' +
                            x.id + ')" class="btn-action ' + tbc +
                            '">' + tbt +
                            '</button><button onclick="openResetModal(' +
                            x.id + ',\'' + esc(x.username) +
                            '\')" class="btn-action btn-reset">Reset Password</button></div></td></tr>'
                    });
                    h += '</tbody></table></div>';
                    c.innerHTML = h
                }
                window.toggleAdmin = function(id) {
                    if (!confirm(
                            'Are you sure you want to change this admin\'s status?'
                        )) return;
                    var fd = new FormData();
                    fd.append('action', 'toggle_status');
                    fd.append('admin_id', id);
                    fetch('api/admins.php', {
                        method: 'POST',
                        body: fd
                    }).then(function(r) {
                        return r.json()
                    }).then(function(d) {
                        if (d.error) {
                            t(d.error, 'error');
                            return
                        }
                        t(d.message, 'success');
                        loadAdmins()
                    }).catch(function() {
                        t('Failed to update.', 'error')
                    })
                };
                window.openCreateModal = function() {
                    document.getElementById('createModal').classList
                        .add('show');
                    document.getElementById('newFullName').value = '';
                    document.getElementById('newUsername').value = '';
                    document.getElementById('newPassword').value = ''
                };
                window.closeCreateModal = function() {
                    document.getElementById('createModal').classList
                        .remove('show')
                };
                document.getElementById('createAdminForm').addEventListener(
                    'submit',
                    function(e) {
                        e.preventDefault();
                        var u = document.getElementById('newUsername')
                            .value.trim(),
                            p = document.getElementById('newPassword')
                            .value.trim(),
                            n = document.getElementById('newFullName')
                            .value.trim();
                        if (!u || !p) {
                            t('Username and password are required.',
                                'error');
                            return
                        }
                        if (p.length < 6) {
                            t('Password must be at least 6 characters.',
                                'error');
                            return
                        }
                        var fd = new FormData();
                        fd.append('action', 'create');
                        fd.append('username', u);
                        fd.append('password', p);
                        fd.append('full_name', n);
                        fetch('api/admins.php', {
                            method: 'POST',
                            body: fd
                        }).then(function(r) {
                            return r.json()
                        }).then(function(d) {
                            if (d.error) {
                                t(d.error, 'error');
                                return
                            }
                            t(d.message, 'success');
                            closeCreateModal();
                            loadAdmins()
                        }).catch(function() {
                            t('Failed to create admin.',
                                'error')
                        })
                    });
                window.openResetModal = function(id, un) {
                    document.getElementById('resetModal').classList.add(
                        'show');
                    document.getElementById('resetAdminId').value = id;
                    document.getElementById('resetAdminLabel')
                        .textContent = 'For: ' + un;
                    document.getElementById('resetNewPassword').value =
                        ''
                };
                window.closeResetModal = function() {
                    document.getElementById('resetModal').classList
                        .remove('show')
                };
                document.getElementById('resetPasswordForm')
                    .addEventListener('submit', function(e) {
                        e.preventDefault();
                        var id = document.getElementById('resetAdminId')
                            .value,
                            p = document.getElementById(
                                'resetNewPassword').value.trim();
                        if (!p || p.length < 6) {
                            t('Password must be at least 6 characters.',
                                'error');
                            return
                        }
                        var fd = new FormData();
                        fd.append('action', 'reset_password');
                        fd.append('admin_id', id);
                        fd.append('new_password', p);
                        fetch('api/admins.php', {
                            method: 'POST',
                            body: fd
                        }).then(function(r) {
                            return r.json()
                        }).then(function(d) {
                            if (d.error) {
                                t(d.error, 'error');
                                return
                            }
                            t(d.message, 'success');
                            closeResetModal()
                        }).catch(function() {
                            t('Failed to reset password.',
                                'error')
                        })
                    });

                function esc(s) {
                    var d = document.createElement('div');
                    d.appendChild(document.createTextNode(s || ''));
                    return d.innerHTML
                }
                document.querySelectorAll('.modal-backdrop').forEach(
                    function(m) {
                        m.addEventListener('click', function(e) {
                            if (e.target === m) m.classList
                                .remove('show')
                        })
                    });
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeCreateModal();
                        closeResetModal()
                    }
                });
                loadAdmins()
            })();
        </script>
    </body>

</html>