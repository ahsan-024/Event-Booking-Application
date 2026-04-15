<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account — TicketFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .auth-bg { background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4c1d95 70%, #6d28d9 100%); }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 auth-bg flex-col justify-between p-12 relative overflow-hidden">
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
                Join thousands of<br>event lovers.
            </h2>
            <p class="text-purple-200 text-lg leading-relaxed mb-8">
                Create your free account and start discovering events near you today.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <div class="text-3xl font-bold text-white mb-1">500+</div>
                    <div class="text-purple-200 text-sm">Events listed</div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <div class="text-3xl font-bold text-white mb-1">10k+</div>
                    <div class="text-purple-200 text-sm">Happy attendees</div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <div class="text-3xl font-bold text-white mb-1">100%</div>
                    <div class="text-purple-200 text-sm">Secure booking</div>
                </div>
                <div class="bg-white/10 rounded-2xl p-4 backdrop-blur">
                    <div class="text-3xl font-bold text-white mb-1">Free</div>
                    <div class="text-purple-200 text-sm">To get started</div>
                </div>
            </div>
        </div>

        <p class="text-purple-300 text-xs relative z-10">© {{ date('Y') }} TicketFlow. All rights reserved.</p>
    </div>

    {{-- Right panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white overflow-y-auto">
        <div class="w-full max-w-md">

            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-purple-700 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold">Ticket<span class="text-violet-600">Flow</span></span>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 mb-2">Create your account</h1>
            <p class="text-gray-500 mb-8">It's free and takes less than a minute</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-6">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('web.register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe"
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com"
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required placeholder="Min. 8 characters"
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm password</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat your password"
                        style="width:100%;background:#f9fafb;border:1px solid #e5e7eb;border-radius:0.75rem;padding:0.875rem 1rem;font-size:0.875rem;color:#111827;box-sizing:border-box;">
                </div>

                <button type="submit"
                    style="width:100%;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#fff;font-weight:600;border-radius:0.75rem;padding:0.875rem;font-size:0.9375rem;border:none;cursor:pointer;margin-top:0.5rem;box-shadow:0 4px 14px rgba(124,58,237,0.4);">
                    Create Free Account
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('web.login') }}" class="text-violet-600 font-semibold hover:text-violet-700">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
