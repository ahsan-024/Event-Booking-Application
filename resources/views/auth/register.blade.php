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
        .input-field {
            width:100%; background:#F7F8F0; border:1.5px solid #c5d8e8;
            border-radius:.75rem; padding:.875rem 1rem; font-size:.875rem;
            color:#1a2e3d; box-sizing:border-box; transition:border-color .15s, box-shadow .15s;
        }
        .input-field:focus { outline:none; border-color:#7AAACE; box-shadow:0 0 0 3px rgba(154,213,255,.3); background:#fff; }
        .input-field::placeholder { color:#8aacbf; }
    </style>
</head>
<body class="min-h-screen flex" style="background:#F7F8F0;">

    {{-- Left panel --}}
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative overflow-hidden" style="background:#355872;">
        <div class="absolute top-0 right-0 w-80 h-80 rounded-full opacity-10" style="background:#9CD5FF;transform:translate(40%,-40%);"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 rounded-full opacity-10" style="background:#7AAACE;transform:translate(-40%,40%);"></div>

        <a href="{{ route('web.events.index') }}" class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#7AAACE;">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                </svg>
            </div>
            <span class="text-white text-xl font-bold">Ticket<span style="color:#9CD5FF;">Flow</span></span>
        </a>

        <div class="relative z-10">
            <h2 class="text-4xl font-bold text-white leading-tight mb-4">
                Join thousands of<br>event lovers.
            </h2>
            <p class="text-lg leading-relaxed mb-8" style="color:#9CD5FF;">
                Create your free account and start discovering events near you today.
            </p>
            <div class="grid grid-cols-2 gap-4">
                @foreach([['500+','Events listed'],['10k+','Happy attendees'],['100%','Secure booking'],['Free','To get started']] as $s)
                <div class="rounded-2xl p-4" style="background:rgba(122,170,206,.2);">
                    <div class="text-3xl font-bold text-white mb-1">{{ $s[0] }}</div>
                    <div class="text-sm" style="color:#9CD5FF;">{{ $s[1] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <p class="text-xs relative z-10" style="color:#7AAACE;">© {{ date('Y') }} TicketFlow. All rights reserved.</p>
    </div>

    {{-- Right panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white overflow-y-auto">
        <div class="w-full max-w-md">

            <div class="lg:hidden flex items-center gap-2 mb-8">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:#355872;">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span class="text-lg font-bold" style="color:#355872;">Ticket<span style="color:#7AAACE;">Flow</span></span>
            </div>

            <h1 class="text-3xl font-bold mb-2" style="color:#1a2e3d;">Create your account</h1>
            <p class="text-sm mb-8" style="color:#6b8fa8;">It's free and takes less than a minute</p>

            @if($errors->any())
                <div class="px-4 py-3 rounded-xl text-sm mb-6" style="background:#fde8e8;border:1px solid #f5b8b8;color:#7f1d1d;">
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
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Full name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="John Doe" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Password</label>
                    <input type="password" name="password" required placeholder="Min. 8 characters" class="input-field">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color:#2a4660;">Confirm password</label>
                    <input type="password" name="password_confirmation" required placeholder="Repeat your password" class="input-field">
                </div>
                <button type="submit"
                    style="width:100%;background:#355872;color:#fff;font-weight:600;border-radius:.75rem;padding:.875rem;font-size:.9375rem;border:none;cursor:pointer;margin-top:.5rem;box-shadow:0 4px 14px rgba(53,88,114,.3);"
                    onmouseover="this.style.background='#2a4660'"
                    onmouseout="this.style.background='#355872'">
                    Create Free Account
                </button>
            </form>

            <p class="text-center text-sm mt-6" style="color:#6b8fa8;">
                Already have an account?
                <a href="{{ route('web.login') }}" class="font-semibold hover:underline" style="color:#355872;">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>
