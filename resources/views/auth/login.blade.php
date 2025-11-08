<x-guest-layout>
    <div class="flex items-center justify-center bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500">
        <div class="rounded-2xl shadow-xl w-full max-w-md p-8 space-y-6 transform transition-all hover:scale-[1.02] duration-300">

            <!-- Logo or Title -->
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Welcome Back 👋</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-800 dark:text-gray-200" />
                    <x-text-input 
                        id="email" 
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg" 
                        type="email" 
                        name="email" 
                        :value="old('email')" 
                        required 
                        autofocus 
                        autocomplete="username" 
                        placeholder="you@example.com"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-gray-800 dark:text-gray-200" />
                    <x-text-input 
                        id="password" 
                        class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg"
                        type="password"
                        name="password"
                        required 
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500" 
                            name="remember"
                        >
                        <span class="ms-2">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <x-primary-button class="w-full justify-center py-3 rounded-lg text-lg bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 dark:focus:ring-indigo-800 transition">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                <!-- 
                <div class="flex items-center justify-center my-4">
                    <span class="h-px w-16 bg-gray-300 dark:bg-gray-700"></span>
                    <span class="mx-2 text-gray-500 dark:text-gray-400 text-sm">or</span>
                    <span class="h-px w-16 bg-gray-300 dark:bg-gray-700"></span>
                </div>

                <div class="flex justify-center space-x-4">
                    <button type="button" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                    </button>
                    <button type="button" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" alt="Facebook">
                    </button>
                    <button type="button" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <img src="https://www.svgrepo.com/show/475689/github-color.svg" class="w-5 h-5" alt="GitHub">
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-6">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-bold">Sign up</a>
            </p> -->
        </div>
    </div>
</x-guest-layout>
