<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Change password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Change password</h1>

    <?php if (!empty($success)): ?>
        <div class="card card--success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="card card--error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">

            <input
                type="password"
                name="current_password"
                placeholder="Current password"
                required
            >

            <input
                type="password"
                name="new_password"
                placeholder="New password"
                required
            >

            <input
                type="password"
                name="new_password_repeat"
                placeholder="Repeat new password"
                required
            >

            <button type="submit" class="btn btn--primary btn--block">
                Change password
            </button>

        </form>
    </div>

</main>

</body>
</html>
