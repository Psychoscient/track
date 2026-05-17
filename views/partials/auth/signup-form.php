<div id="signupForm" class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="fname" class="block text-sm font-semibold text-ust-dark mb-2">
                First Name
            </label>
            <input
                type="text"
                id="fname"
                name="fname"
                required
                placeholder="Enter first name"
                data-type="fname"
                class="input-field w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
            >
        </div>

        <div>
            <label for="lname" class="block text-sm font-semibold text-ust-dark mb-2">
                Surname
            </label>
            <input
                type="text"
                id="lname"
                name="lname"
                required
                placeholder="Enter surname"
                data-type="fname"
                class="input-field w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
            >
        </div>
    </div>

    <div>
        <label for="email" class="block text-sm font-semibold text-ust-dark mb-2">
            Email Address
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
        <label for="password" class="block text-sm font-semibold text-ust-dark mb-2">
            Password
        </label>
        <input
            type="password"
            id="password"
            name="password"
            required
            placeholder="Create a password"
            class="input-field w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-sm font-body text-ust-dark placeholder-gray-400 focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition bg-ust-cream"
        >
    </div>

    <div>
        <label for="yearlvl" class="block text-sm font-semibold text-ust-dark mb-2">
            Year Level
        </label>
        <select
            id="yearlvl"
            name="yearlvl"
            required
            class="input-field w-full rounded-xl border-2 border-gray-200 bg-ust-cream px-4 py-3 text-sm font-body text-ust-dark focus:border-ust-gold focus:ring-2 focus:ring-ust-gold/20 transition"
        >
            <option value="">Choose Year Level</option>
            <?php foreach ($yearlvl as $year): ?>
                <option value="<?= $year['year_lvl_id'] ?>">
                    <?= $year['year_lvl_name'] ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button
        id="submit"
        type="submit"
        class="w-full bg-ust-gold hover:bg-ust-gold-dark text-ust-dark font-semibold py-3 rounded-xl shadow-ust transition duration-200 flex items-center justify-center gap-2"
    >
        <i class="fas fa-user-plus"></i>
        Create Account
    </button>

    <div class="relative my-4">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="px-3 bg-white text-ust-gray">or</span>
        </div>
    </div>

    <div class="rounded-2xl bg-white/75 border border-ust-gold/10 px-4 py-5 text-center backdrop-blur-sm">
        <p class="text-sm text-ust-gray">
            Already have an account?
        </p>
        <a href="login.php" class="inline-block px-6 py-2 border-2 border-ust-gold text-ust-gold hover:bg-ust-gold/5 font-semibold rounded-xl transition mt-3">
            Log In
        </a>
    </div>
</div>
