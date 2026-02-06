<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH – Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="content">

    <h1>Hi, <?= htmlspecialchars($_SESSION['user_firstname']) ?>!</h1>

    <?php if ($_SESSION['user_role'] !== 'USER'): ?>
        <div class="admin-panel">
            <a href="/assignments" class="admin-tile">Manage assignments</a>
            <a href="/manage-users" class="admin-tile">Manage users</a>
            <a href="/manage-companies" class="admin-tile">Manage companies</a>
        </div>
    <?php endif; ?>

    <h2>Your assignments:</h2>

    <div class="files-list">

        <?php if (empty($assignments)): ?>
            <p>No assignments yet.</p>
        <?php endif; ?>

        <?php foreach ($assignments as $a): ?>
            <div class="file-tile">
                            <div class="file-info">
                <span>🎬</span>

                <span class="file-name">
                    <?= htmlspecialchars($a['title']) ?>
                    <span class="company-inline">
                        <?= htmlspecialchars($a['company'] ?? '— no company —') ?>
                    </span>
                </span>
            </div>

                <a href="/assignment/<?= $a['id'] ?>" class="watch-btn">
                    Watch
                </a>
            </div>
        <?php endforeach; ?>

    </div>

</main>

</body>
</html>
