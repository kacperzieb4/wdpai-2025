<header class="header">
    <img src="/public/images/logo.png" alt="FINCH logo" class="logo">

    <nav class="nav">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard">Dashboard</a>
            <a href="/change-password">Change password</a>
            <a href="/logout" class="login-btn">Logout</a>
        <?php else: ?>
            <a href="#about">About me</a>
            <a href="#contact">Contact</a>
            <a href="/login" class="login-btn">Login</a>
        <?php endif; ?>
    </nav>

    <div class="burger">☰</div>
</header>
