<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= $code ?> – <?= htmlspecialchars($title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">

    <link rel="stylesheet" href="/public/styles/pages/error.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center error-page">

    <div class="error-card card">
        <div class="error-code"><?= $code ?></div>

        <h1 class="error-title">
            <?= htmlspecialchars($title) ?>
        </h1>

        <p class="error-message">
            <?= htmlspecialchars($message) ?>
        </p>

        <div class="error-actions">
            <a href="/" class="btn btn--block">Home</a>
            <a href="/dashboard" class="btn btn--block">Dashboard</a>
        </div>
    </div>

</main>

</body>
</html>
