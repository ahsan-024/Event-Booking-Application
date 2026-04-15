<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — TicketFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-bg { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 70%, #6d28d9 100%); }
        .input-field { width:100%; background:#f9fafb; border:1px solid #e5e7eb; border-radius:0.75rem; padding:0.875rem 1rem; font-size:0.875rem; color:#111827; transition:all 0.2s; }
        .input-field:focus { outline:none; ring:2px; border-color:#7c3aed; background:#fff; box-shadow:0 0 0 3px rgba(124,58,237,0.1); }
        .input-field::placeholder { color:#9ca3af; }
    </style>
</head>
<body class="min-h-screen flex" style="font-family:'Inter',sans-serif;">

    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 auth-bg flex-col justify-between p-12 relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

        <a href="{{ route('web.events.index') }}" class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <span class="text-white text-xl font-bold">TicketFlow</span>
        </a>

        <div class="relative z-10">
            <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                Your next great<br>experience awaits.
            </h2>
            <p class="text-purple-200 text-lg leading-relaxed mb-8">
                Discover thousands of events, book seats instantly, and create memories that last a lifetime.
            </p>
            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3 text-purple-100">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm">Instant booking confirmation</span>
                </div>
                <div class="flex items-center gap-3 text-purple-100">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm">Secure seat reservation</span>
                </div>
                <div class="flex items-center gap-3 text-purple-100">
                    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-sm">Easy cancellation anytime</span>
                </div>
            </div>
        </div>

        <p class="text-purple-300 text-xs relative z-10">© {{ date('Y') }} TicketFlow. All rights reserved.</p>
    </div>

    {{-- Right panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-purple-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold">Ticket<span class="text-violet-600">Flow</span></span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back</h1>
            <p class="text-gray-500 mb-8">Sign in to your account to continue</p>

            @if($errors->any())
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('web.login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com"
                        class="input-field" style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;transition:all 0.2s;">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-semibold text-gray-700">Password</label>
                    </div>
                    <input type="password" name="password" required placeholder="••••••••"
                        class="input-field" style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;transition:all 0.2s;">
                </div>

                <button type="submit"
                    style="width:100%;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-weight:600;border-radius:0.75rem;padding:0.875rem;font-size:0.9375rem;border:none;cursor:pointer;transition:all 0.2s;box-shadow:0 4px 14px rgba(124,58,237,0.4);"
                    onmouseover="this.style.background='linear-gradient(135deg,#6d28d9,#5b21b6)'"
                    onmouseout="this.style.background='linear-gradient(135deg,#7c3aed,#6d28d9)'">
                    Sign In
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ route('web.register') }}" class="text-violet-600 font-semibold hover:text-violet-700">Create one free</a>
            </p>
        </div>
    </div>
</body>
</html>
