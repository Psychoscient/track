<form id="resetForm" class="space-y-6">
    <input type="hidden" name="action" value="reset-password">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? '', ENT_QUOTES); ?>">

    <div>
        <label for="newPassword" class="block text-sm font-semibold text-ust-dark mb-2">
            New Password <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <input
            type="password"
            id="newPassword"
            name="newPassword"
            required
            placeholder="Create a strong password"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
        >
    </div>

    <div>
        <label for="confirmPassword" class="block text-sm font-semibold text-ust-dark mb-2">
            Confirm Password <span class="text-red-600" aria-hidden="true">*</span>
        </label>
        <input
            type="password"
            id="confirmPassword"
            name="confirmPassword"
            required
            placeholder="Confirm your password"
            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
        >
    </div>

    <button
        type="submit"
        class="w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-xl shadow-ust transition duration-200 flex items-center justify-center gap-2"
    >
        <i class="fas fa-lock"></i>
        Reset Password
    </button>

    <div class="rounded-2xl bg-white/75 border border-ust-gold/10 px-4 py-5 text-center backdrop-blur-sm">
        <a href="forgot-password.php" class="text-sm text-ust-gold hover:text-ust-gold-dark font-medium transition">
            <i class="fas fa-redo mr-1"></i>
            Request New Link
        </a>
    </div>
</form>
