<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Assignments</title>
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

    <h1 class="page-title">Assignments</h1>

    <?php if ($_SESSION['user_role'] !== 'USER'): ?>
        <div class="admin-panel">
            <a href="/create-assignment" class="admin-tile">
                ➕ Create assignment
            </a>
        </div>
    <?php endif; ?>

    <h2 class="section-title">Assignments list:</h2>

    <div class="list">
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
                View
            </a>

            <?php if ($_SESSION['user_role'] !== 'USER'): ?>
                <a href="/edit-assignment/<?= $a['id'] ?>" class="action-link">
                    Edit
                </a>

                <a href="/delete-assignment/<?= $a['id'] ?>"
                class="action-link action-link--danger">
                    Delete
                </a>
            <?php endif; ?>
        </div>

    </div>

    <?php endforeach; ?>
</div>


</main>

</body>
</html>
