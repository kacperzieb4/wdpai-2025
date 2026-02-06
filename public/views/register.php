<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH – Activate account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="public/styles/login.css">
    <link rel="stylesheet" href="public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<div class="bg-image"></div>
<div class="overlay"></div>

<div class="login-box">
    <img src="public/images/logo.png" class="login-logo">

    <?php if (isset($messages)): ?>
        <div class="messages">
            <?php foreach ($messages as $msg): ?>
                <p><?= $msg ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register-submit">
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="code" placeholder="Activation code" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password2" placeholder="Repeat password" required>
        <button type="submit">Activate account</button>
    </form>
</div>

</body>
</html>
