<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Manage users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="content">

    <h1>Users</h1>

    <div class="admin-panel">
        <a href="/create-user" class="admin-tile">
            ➕ Create user
        </a>
    </div>

    <h2>User list:</h2>

    <div class="files-list">
        <?php foreach ($users as $u): ?>

            <?php
                $currentUserId = $_SESSION['user_id'];
                $currentRole   = $_SESSION['user_role'];
                $targetRole    = $u['role'];
            ?>

            <div class="file-tile">
                <div class="file-info">
                    <span>👤</span>

                    <span class="file-name">
                        <?= htmlspecialchars($u['firstname']) ?>
                        <?= htmlspecialchars($u['lastname']) ?>

                        <?php if (!empty($u['role'])): ?>
                            <span class="role-badge <?= strtolower($u['role']) ?>">
                                <?= htmlspecialchars($u['role']) ?>
                            </span>
                        <?php endif; ?>
                    </span>

                    <small>
                        <?= $u['company'] ?? '— no company —' ?>
                    </small>
                </div>

               <div class="file-actions">
                    <?php
                        $currentUserId = $_SESSION['user_id'];
                        $currentRole   = $_SESSION['user_role'];
                        $targetRole    = $u['role'];
                        $isSelf        = $u['id'] == $currentUserId;
                    ?>

                    <?php if (
                        $currentRole === 'ADMIN'
                        || ($currentRole === 'MODERATOR' && $targetRole === 'USER')
                    ): ?>
                        <?php if (!$isSelf): ?>
                            <a href="/edit-user/<?= $u['id'] ?>" class="watch-btn">
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
                        <a href="/delete-user/<?= $u['id'] ?>" class="watch-btn">
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
