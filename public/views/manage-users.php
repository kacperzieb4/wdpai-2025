<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Manage users</title>
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

    <h1 class="page-title">Users</h1>

    <div class="admin-panel">
        <a href="/create-user" class="admin-tile">
            ➕ Create user
        </a>
    </div>

    <h2 class="section-title">User list:</h2>

    <div class="list">
        <?php foreach ($users as $u): ?>

            <?php
                $currentUserId = $_SESSION['user_id'];
                $currentRole   = $_SESSION['user_role'];
                $targetRole    = $u['role'];
                $isSelf        = $u['id'] == $currentUserId;
            ?>

            <div class="list-item">

                <div class="list-item__main">
                    <span class="list-item__icon">👤</span>

                    <div class="list-item__text">
                        <span class="list-item__title">
                            <?= htmlspecialchars($u['firstname']) ?>
                            <?= htmlspecialchars($u['lastname']) ?>

                            <?php if (!empty($u['role'])): ?>
                                <span class="role-badge <?= strtolower($u['role']) ?>">
                                    <?= htmlspecialchars($u['role']) ?>
                                </span>
                            <?php endif; ?>
                        </span>

                        <span class="list-item__meta">
                            <?= htmlspecialchars($u['company'] ?? '— no company —') ?>
                        </span>
                    </div>
                </div>

                <div class="list-item__actions">

                    <?php if (
                        ($currentRole === 'ADMIN')
                        || ($currentRole === 'MODERATOR' && $targetRole === 'USER')
                    ): ?>
                        <?php if (!$isSelf): ?>
                            <a href="/edit-user/<?= $u['id'] ?>" class="action-link">
                                Edit
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (
                        !$isSelf
                        && (
                            $currentRole === 'ADMIN'
                            || ($currentRole === 'MODERATOR' && $targetRole === 'USER')
                        )
                    ): ?>
                        <a href="/delete-user/<?= $u['id'] ?>"
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
