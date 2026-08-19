<div class="min-h-screen flex flex-col md:flex-row font-sans antialiased bg-white">
    <!-- ============ LEFT PANEL — HERO ============ -->
    <div class="relative w-full md:w-1/2 min-h-[420px] md:min-h-screen flex flex-col justify-between overflow-hidden bg-gradient-to-br from-blue-600 via-blue-500 to-blue-300">
        <!-- subtle building/window texture overlay -->
        <div class="absolute inset-0 opacity-20"
             style="background-image: repeating-linear-gradient(0deg, rgba(255,255,255,0.25) 0 2px, transparent 2px 34px), repeating-linear-gradient(90deg, rgba(255,255,255,0.25) 0 2px, transparent 2px 34px);"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 via-transparent to-white/10"></div>

        <!-- content -->
        <div class="relative z-10 flex flex-col h-full justify-between p-10 md:p-14">
            <!-- badge -->
            <div>
                <span class="inline-block px-4 py-2 rounded-full bg-white/15 border border-white/25 text-white text-sm font-medium backdrop-blur-sm">
                    Enterprise Solution
                </span>
            </div>

            <!-- headline + copy -->
            <div class="mt-10 md:mt-0">
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-white">
                    Optimizing<br />
                    Construction<br />
                    <span class="text-blue-100/70">Lifecycle</span><br />
                    <span class="text-blue-100/70">Management.</span>
                </h1>

                <p class="mt-6 max-w-md text-blue-50/90 text-base leading-relaxed">
                    Precision in every build. PT Bintang Gandari EVM Dashboard provides
                    real-time insights for your large-scale construction projects.
                </p>
            </div>

            <!-- stats -->
            <div class="flex gap-10 mt-10">
                <div>
                    <p class="text-3xl font-extrabold text-white">100+</p>
                    <p class="text-xs tracking-widest text-blue-50/80 uppercase mt-1">Active Projects</p>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-white">98%</p>
                    <p class="text-xs tracking-widest text-blue-50/80 uppercase mt-1">Efficiency Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ RIGHT PANEL — FORM ============ -->
    <div class="relative w-full md:w-1/2 flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-12 bg-white">
        <div class="w-full max-w-md mx-auto">
            <!-- logo / brand -->
            <div class="flex items-center gap-3 mb-10">
                <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center shrink-0 shadow-lg shadow-blue-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 leading-none">PT Bintang Gandari</p>
                    <p class="text-xs font-bold tracking-widest text-blue-600 mt-1">EVM DASHBOARD</p>
                </div>
            </div>

            <!-- heading -->
            <h2 class="text-3xl font-extrabold text-gray-900">Welcome Back</h2>
            <p class="text-gray-500 mt-2">Sign in to manage and monitor construction projects</p>

            <!-- FORM -->
            <form wire:submit="login" class="mt-8 space-y-5">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-800 mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" id="username" wire:model="username" placeholder="Enter your username" required autocomplete="username"
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                    </div>
                </div>

                <!-- Password -->
                <div x-data="{ showPassword: false }">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-800">
                            Password
                        </label>
                        <a href="#" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input :type="showPassword ? 'text' : 'password'" id="password" wire:model="password" placeholder="Enter your password" required autocomplete="current-password"
                               class="w-full pl-11 pr-11 py-3.5 rounded-xl border border-gray-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />
                        <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none" tabindex="-1">
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input type="checkbox" id="remember" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                    <label for="remember" class="ml-2.5 text-sm text-gray-700 cursor-pointer">
                        Remember me
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold transition shadow-sm hover:shadow-md active:scale-[0.99]">
                    Sign In to Dashboard
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <!-- footer links -->
            <div class="flex items-center justify-between mt-8">
                <p class="text-sm text-gray-500">
                    Don't have an account?{' '}
                    <a href="#" class="font-semibold text-blue-600 hover:text-blue-700">
                        Request Access
                    </a>
                </p>
            </div>
        </div>

        <!-- system status badge -->
        <div class="hidden md:flex items-center gap-2 absolute bottom-8 right-10 px-4 py-2 rounded-full border border-gray-200 bg-white shadow-sm">
            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
            <span class="text-sm text-gray-600">System Status: Online</span>
        </div>

        <!-- help button -->
        <button class="hidden md:flex items-center justify-center absolute bottom-8 right-[calc(2.5rem+11.5rem+0.75rem)] w-9 h-9 rounded-full bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition">
            ?
        </button>
    </div>
</div>
