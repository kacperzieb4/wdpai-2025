<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Create user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Create user</h1>

    <?php if (!empty($successCode)): ?>
        <div class="card">
            <strong>User created successfully.</strong><br><br>
            Activation code:
            <div class="file-name" style="margin-top:8px;">
                <?= htmlspecialchars($successCode) ?>
            </div>
        </div>
    <?php endif; ?>

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
                placeholder="Email"
                required
            >

            <input
                type="text"
                name="firstname"
                placeholder="First name"
                required
            >

            <input
                type="text"
                name="lastname"
                placeholder="Last name"
                required
            >

            <select name="role" id="roleSelect" required>
                <option value="" disabled selected>Role</option>
                <?php foreach ($roles as $role): ?>
                    <?php
                        if (
                            $_SESSION['user_role'] === 'MODERATOR'
                            && $role === 'ADMIN'
                        ) continue;
                    ?>
                    <option value="<?= $role ?>">
                        <?= $role ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="company" id="companySelect">
                <option value="" disabled selected>Company</option>
                <?php foreach ($companies as $company): ?>
                    <option value="<?= $company['id'] ?>">
                        <?= htmlspecialchars($company['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn--primary btn--block">
                Create user
            </button>

        </form>
    </div>

</main>

<script src="/public/scripts/user-role.js"></script>

</body>
</html>
