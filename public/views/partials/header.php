<header class="header">
    <div class="header-inner">

        <a href="/">
            <img
                src="/public/images/logo.png"
                alt="FINCH MEDIA"
                class="logo"
            >
        </a>

        <input type="checkbox" id="nav-toggle" class="nav-toggle">

        <label for="nav-toggle" class="nav-hamburger">
            <span></span>
            <span></span>
            <span></span>
        </label>

        <nav class="nav">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="/dashboard" class="header-link">Dashboard</a>
                <a href="/change-password" class="header-link">Change password</a>
                <a href="/logout" class="btn btn--primary">Logout</a>

            <?php else: ?>

                <a href="#about" class="header-link">About me</a>
                <a href="#contact" class="header-link">Contact</a>
                <a href="/login" class="btn btn--primary">Login</a>

            <?php endif; ?>

        </nav>

    </div>
</header>
