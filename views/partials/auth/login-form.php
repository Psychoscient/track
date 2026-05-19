<div id="loginForm" class="space-y-6">
    <div>
        <label for="email" class="block text-sm font-semibold text-ust-dark mb-2">
            Email Address <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <input
            type="email"
            id="email"
            name="email"
            required
            placeholder="Enter your email"
            class="input-field w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
        >
    </div>

    <div>
        <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-ust-dark">
                Password <span class="text-red-600" aria-hidden="true">*</span>
            </label>
            <a href="forgot-password.php" class="text-xs text-ust-gold hover:text-ust-gold-dark font-medium transition">
                Forgot password?
            </a>
        </div>

        <input
            type="password"
            id="password"
            name="password"
            required
            placeholder="Enter your password"
            class="input-field w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
        >
    </div>

    <button
        id="submit"
        type="submit"
        class="w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-xl shadow-ust transition duration-200 flex items-center justify-center gap-2"
    >
        <i class="fas fa-sign-in-alt"></i>
        Log In
    </button>

    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="px-3 bg-white text-ust-gray">or</span>
        </div>
    </div>

    <div class="rounded-2xl bg-white/75 border border-ust-gold/10 px-4 py-5 text-center backdrop-blur-sm">
        <p class="text-sm text-ust-gray mb-3">
            New to the platform?
        </p>
        <a href="signup.php" class="inline-block px-6 py-2 border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 font-semibold rounded-xl transition">
            Create Account
        </a>
    </div>
</div>
