<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH – Activate account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/header.css">

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

        <form method="POST" action="/register-submit" class="login-form">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">

            <input
                type="email"
                name="email"
                class="login-input"
                placeholder="Email"
                required
            >

            <input
                type="text"
                name="code"
                class="login-input"
                placeholder="Activation code"
                required
            >

            <input
                type="password"
                name="password"
                class="login-input"
                placeholder="Password"
                required
            >

            <input
                type="password"
                name="password2"
                class="login-input"
                placeholder="Repeat password"
                required
            >

            <button type="submit" class="login-button">
                Activate account
            </button>

        </form>

    </section>

</main>

</body>
</html>
