<header class="header">
    <div class="header-inner">

        <img src="/public/images/logo.png" class="logo">

        <nav class="nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/dashboard">Dashboard</a>
                <a href="/change-password">Change password</a>
                <a href="/logout" class="btn btn--primary">Logout</a>
            <?php else: ?>
                <a href="#about">About me</a>
                <a href="#contact">Contact</a>
                <a href="/login" class="btn btn--primary">Login</a>
            <?php endif; ?>
        </nav>

    </div>
</header>
