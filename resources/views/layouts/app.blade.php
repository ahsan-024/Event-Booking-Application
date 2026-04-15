<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TicketFlow — Find & Book Events')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:   #355872;
            --mid:    #7AAACE;
            --light:  #9CD5FF;
            --cream:  #F7F8F0;
        }
        body { font-family: 'Inter', sans-serif; background: var(--cream); }

        /* Navbar */
        .navbar { background: var(--navy); }
        .nav-link { color: #c8dff0; transition: color .15s; }
        .nav-link:hover { color: var(--light); }

        /* Buttons */
        .btn-primary {
            background: var(--navy);
            color: #fff;
            border-radius: .75rem;
            padding: .625rem 1.25rem;
            font-weight: 600;
            font-size: .875rem;
            border: none;
            cursor: pointer;
            transition: background .15s, box-shadow .15s;
            box-shadow: 0 2px 8px rgba(53,88,114,.25);
        }
        .btn-primary:hover { background: #2a4660; box-shadow: 0 4px 14px rgba(53,88,114,.35); }

        .btn-outline {
            background: transparent;
            color: var(--navy);
            border: 1.5px solid var(--mid);
            border-radius: .75rem;
            padding: .625rem 1.25rem;
            font-weight: 500;
            font-size: .875rem;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-outline:hover { background: #e8f4ff; }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #dde8f0;
            box-shadow: 0 1px 4px rgba(53,88,114,.07);
            transition: transform .2s, box-shadow .2s;
        }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(53,88,114,.13); }

        /* Inputs */
        .input-field {
            width: 100%;
            background: var(--cream);
            border: 1.5px solid #c5d8e8;
            border-radius: .75rem;
            padding: .8125rem 1rem;
            font-size: .875rem;
            color: #1a2e3d;
            box-sizing: border-box;
            transition: border-color .15s, box-shadow .15s;
        }
        .input-field:focus {
            outline: none;
            border-color: var(--mid);
            box-shadow: 0 0 0 3px rgba(154,213,255,.35);
            background: #fff;
        }
        .input-field::placeholder { color: #8aacbf; }

        /* Badges */
        .badge-available { display:inline-flex;align-items:center;gap:.25rem;background:#e0f4ff;color:#2a6a9a;font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px; }
        .badge-soldout   { display:inline-flex;align-items:center;gap:.25rem;background:#fde8e8;color:#b91c1c;font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px; }
        .badge-booked    { display:inline-flex;align-items:center;gap:.25rem;background:#e0f4ff;color:var(--navy);font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px; }
        .badge-cancelled { display:inline-flex;align-items:center;gap:.25rem;background:#f0f0f0;color:#6b7280;font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px; }

        /* Alert */
        .alert-success { background:#e6f7ee;border:1px solid #a7d7b8;color:#1a5c35; }
        .alert-error   { background:#fde8e8;border:1px solid #f5b8b8;color:#7f1d1d; }

        /* Dropdown */
        .dropdown { position:relative; }
        .dropdown-menu {
            position:absolute;right:0;top:calc(100% + .25rem);
            background:#fff;border-radius:.875rem;
            border:1px solid #dde8f0;
            box-shadow:0 8px 24px rgba(53,88,114,.15);
            min-width:11rem;padding:.375rem 0;
            opacity:0;visibility:hidden;transition:opacity .15s;z-index:50;
        }
        .dropdown:hover .dropdown-menu { opacity:1;visibility:visible; }
        .dropdown-item { display:flex;align-items:center;gap:.5rem;padding:.625rem 1rem;font-size:.8125rem;color:#2a4660;transition:background .1s; }
        .dropdown-item:hover { background:#f0f7ff; }
        .dropdown-divider { border-top:1px solid #e8f0f7;margin:.25rem 0; }
    </style>
</head>
<body class="min-h-screen flex flex-col antialiased">

    {{-- Navbar --}}
    <header class="navbar sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">

                {{-- Logo --}}
                <a href="{{ route('web.events.index') }}" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow" style="background:#7AAACE;">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white tracking-tight">Ticket<span style="color:#9CD5FF;">Flow</span></span>
                </a>

                {{-- Nav links --}}
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('web.events.index') }}" class="nav-link px-4 py-2 text-sm font-medium rounded-lg hover:bg-white/10 transition-all">Browse Events</a>
                    @auth
                        <a href="{{ route('web.bookings.index') }}" class="nav-link px-4 py-2 text-sm font-medium rounded-lg hover:bg-white/10 transition-all">My Bookings</a>
                    @endauth
                </nav>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('web.events.create') }}"
                            class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold px-4 py-2 rounded-xl transition-all"
                            style="background:#7AAACE;color:#fff;">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create Event
                        </a>
                        <div class="dropdown">
                            <button class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-white/10 transition-all">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background:#7AAACE;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-white/90 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 text-white/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('web.bookings.index') }}" class="dropdown-item">
                                    <svg class="w-4 h-4" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                    My Bookings
                                </a>
                                <a href="{{ route('web.events.create') }}" class="dropdown-item">
                                    <svg class="w-4 h-4" style="color:#7AAACE;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Create Event
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('web.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item w-full text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('web.login') }}" class="nav-link text-sm font-medium px-4 py-2 rounded-lg hover:bg-white/10 transition-all">Sign In</a>
                        <a href="{{ route('web.register') }}" class="text-sm font-semibold px-4 py-2 rounded-xl transition-all" style="background:#7AAACE;color:#fff;">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Toast notifications --}}
    @if(session('success') || session('error'))
    <div id="toast-container" style="position:fixed;top:1.25rem;right:1.25rem;z-index:9999;display:flex;flex-direction:column;gap:.75rem;pointer-events:none;">
        @if(session('success'))
        <div class="toast" style="
            display:flex;align-items:center;gap:.75rem;
            background:#fff;border-left:4px solid #7AAACE;
            border-radius:.875rem;padding:.875rem 1.125rem;
            box-shadow:0 8px 24px rgba(53,88,114,.18);
            min-width:280px;max-width:380px;
            font-size:.875rem;font-weight:500;color:#1a2e3d;
            pointer-events:all;
            animation:toastIn .3s ease forwards;">
            <div style="width:2rem;height:2rem;border-radius:50%;background:#e0f4ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1rem;height:1rem;color:#355872;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <span>{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="toast" style="
            display:flex;align-items:center;gap:.75rem;
            background:#fff;border-left:4px solid #ef4444;
            border-radius:.875rem;padding:.875rem 1.125rem;
            box-shadow:0 8px 24px rgba(53,88,114,.18);
            min-width:280px;max-width:380px;
            font-size:.875rem;font-weight:500;color:#1a2e3d;
            pointer-events:all;
            animation:toastIn .3s ease forwards;">
            <div style="width:2rem;height:2rem;border-radius:50%;background:#fde8e8;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg style="width:1rem;height:1rem;color:#ef4444;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <span>{{ session('error') }}</span>
        </div>
        @endif
    </div>
    <style>
        @keyframes toastIn {
            from { opacity:0; transform:translateX(2rem); }
            to   { opacity:1; transform:translateX(0); }
        }
        @keyframes toastOut {
            from { opacity:1; transform:translateX(0); }
            to   { opacity:0; transform:translateX(2rem); }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toasts = document.querySelectorAll('.toast');
            toasts.forEach(function (toast) {
                setTimeout(function () {
                    toast.style.animation = 'toastOut .4s ease forwards';
                    setTimeout(function () { toast.remove(); }, 400);
                }, 2000);
            });
        });
    </script>
    @endif

    <main class="flex-1">@yield('content')</main>

    <footer style="background:#355872;color:#9CD5FF;" class="mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#7AAACE;">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-white">Ticket<span style="color:#9CD5FF;">Flow</span></span>
                </div>
                <p class="text-sm" style="color:#9CD5FF;">© {{ date('Y') }} TicketFlow. Discover and book amazing events.</p>
            </div>
        </div>
    </footer>
</body>
</html>
