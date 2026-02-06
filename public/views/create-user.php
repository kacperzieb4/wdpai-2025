<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Create user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="stylesheet" href="/public/styles/create-user.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="create-user-wrapper">

    <h1>Create user</h1>

    <?php if (!empty($successCode)): ?>
        <div class="message-box">
            User created successfully.<br>
            Activation code:
            <div class="activation-code">
                <?= htmlspecialchars($successCode) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="message-box error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="create-user-card">
        <form method="POST" class="create-user-form">

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

            <button type="submit">Create user</button>

        </form>
    </div>

</main>

<script>
const roleSelect = document.getElementById('roleSelect');
const companySelect = document.getElementById('companySelect');

function updateCompanyField() {
    if (roleSelect.value === 'ADMIN' || roleSelect.value === 'MODERATOR') {
        companySelect.value = '1';
        companySelect.disabled = true;
    } else {
        companySelect.disabled = false;
    }
}

roleSelect.addEventListener('change', updateCompanyField);
</script>

</body>
</html>
