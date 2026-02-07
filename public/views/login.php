<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH – Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- global -->
    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/header.css">

    <!-- page -->
    <link rel="stylesheet" href="/public/styles/pages/login.css">
</head>

<body class="login-page">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="login-scene">

    <div class="login-bg"></div>
    <div class="login-overlay"></div>

    <section class="login-panel">

        <img
            src="/public/images/logo.png"
            alt="FINCH MEDIA"
            class="login-logo"
        >

        <?php if (!empty($messages)): ?>
            <div class="login-messages">
                <?php foreach ($messages as $msg): ?>
                    <p><?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" class="login-form">

            <input
                type="email"
                name="email"
                class="login-input"
                placeholder="email"
                required
            >

            <input
                type="password"
                name="password"
                class="login-input"
                placeholder="password"
                required
            >

            <button type="submit" class="login-button">
                Login
            </button>

        </form>

        <p class="login-activate">
            <a href="/register">Activate your account here!</a>
        </p>

    </section>

</main>

</body>
</html>
