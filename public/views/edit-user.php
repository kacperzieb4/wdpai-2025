<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edit user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Edit user</h1>

    <?php if (!empty($error)): ?>
        <div class="card" style="background:#ffdddd;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">

            <input
                type="email"
                name="email"
                value="<?= htmlspecialchars($user['email']) ?>"
                readonly
            >

            <input
                type="text"
                name="firstname"
                value="<?= htmlspecialchars($user['firstname']) ?>"
                placeholder="First name"
                required
            >

            <input
                type="text"
                name="lastname"
                value="<?= htmlspecialchars($user['lastname']) ?>"
                placeholder="Last name"
                required
            >

            <select name="role_id" id="roleSelect" required>
                <?php foreach ($roles as $role): ?>
                    <?php
                        if (
                            $_SESSION['user_role'] === 'MODERATOR'
                            && $role['name'] === 'ADMIN'
                        ) continue;
                    ?>
                    <option value="<?= $role['id'] ?>"
                        <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="company_id" id="companySelect">
                <?php foreach ($companies as $company): ?>
                    <option value="<?= $company['id'] ?>"
                        <?= $company['id'] == $user['company_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($company['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn--primary btn--block">
                Save changes
            </button>

        </form>
    </div>

</main>

<script src="/public/scripts/user-role.js"></script>

</body>
</html>
