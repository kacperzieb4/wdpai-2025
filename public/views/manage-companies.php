<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Manage companies</title>
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

    <h1 class="page-title">Companies</h1>

    <div class="admin-panel">
        <a href="/create-company" class="admin-tile">
            ➕ Create company
        </a>
    </div>

    <h2 class="section-title">Company list:</h2>

    <div class="list">
        <?php foreach ($companies as $c): ?>
            <div class="list-item">

                <div class="list-item__main">
                    <span class="list-item__icon">🏢</span>

                    <div class="list-item__text">
                        <span class="list-item__title">
                            <?= htmlspecialchars($c['name']) ?>
                        </span>
                    </div>
                </div>

                <div class="list-item__actions">
                    <?php if (empty($c['is_protected'])): ?>

                        <a href="/edit-company/<?= $c['id'] ?>" class="action-link">
                            Edit
                        </a>

                        <a href="/delete-company/<?= $c['id'] ?>"
                        class="action-link action-link--danger">
                            Delete
                        </a>

                        <a href="/delete-company-with-users/<?= $c['id'] ?>"
                        class="action-link action-link--danger">
                            Delete with users
                        </a>

                    <?php else: ?>

                        <span class="action-link action-link--disabled">
                            Protected
                        </span>

                    <?php endif; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</main>

</body>
</html>