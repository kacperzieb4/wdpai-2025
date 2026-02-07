<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH – Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
    <link rel="stylesheet" href="/public/styles/home.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Hi, <?= htmlspecialchars($_SESSION['user_firstname']) ?>!</h1>

    <?php if ($_SESSION['user_role'] !== 'USER'): ?>
        <div class="admin-panel">
            <a href="/assignments" class="admin-tile">Manage assignments</a>
            <a href="/manage-users" class="admin-tile">Manage users</a>
            <a href="/manage-companies" class="admin-tile">Manage companies</a>
        </div>
    <?php endif; ?>

    <h2 class="section-title">Your assignments:</h2>

    <div class="list">

        <?php if (empty($assignments)): ?>
            <p>No assignments yet.</p>
        <?php endif; ?>

        <?php foreach ($assignments as $a): ?>
            <div class="list-item">

                <div class="list-item__main">
                    <span class="list-item__icon">🎬</span>

                    <div class="list-item__text">
                        <span class="list-item__title">
                            <?= htmlspecialchars($a['title']) ?>
                        </span>

                        <span class="list-item__meta">
                            <?= htmlspecialchars($a['company'] ?? '— no company —') ?>
                        </span>
                    </div>
                </div>

                <div class="list-item__actions">
                    <a href="/assignment/<?= $a['id'] ?>" class="action-link">
                        Watch
                    </a>
                </div>

            </div>
        <?php endforeach; ?>

    </div>

</main>

</body>
</html>
