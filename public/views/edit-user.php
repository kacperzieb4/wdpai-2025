<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edit user</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="stylesheet" href="/public/styles/create-user.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="content">

    <h1 class="page-title">Edit user</h1>

    <?php if (!empty($error)): ?>
        <div class="error-box">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">

            <input type="email"
                   name="email"
                   value="<?= htmlspecialchars($user['email']) ?>"
                   readonly>

            <input type="text"
                   name="firstname"
                   value="<?= htmlspecialchars($user['firstname']) ?>"
                   required>

            <input type="text"
                   name="lastname"
                   value="<?= htmlspecialchars($user['lastname']) ?>"
                   required>

            <select name="role_id" id="roleSelect" required>
                <?php foreach ($roles as $role): ?>
                    <?php
                        if ($_SESSION['user_role'] === 'MODERATOR' && $role['name'] === 'ADMIN') {
                            continue;
                        }
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

            <button type="submit" class="btn-primary">
                Save changes
            </button>

        </form>
    </div>

</main>

<script>
const roleSelect = document.getElementById('roleSelect');
const companySelect = document.getElementById('companySelect');

function updateCompanyField() {
    const roleText = roleSelect.options[roleSelect.selectedIndex].text;

    if (roleText === 'ADMIN' || roleText === 'MODERATOR') {
        companySelect.disabled = true;
        companySelect.removeAttribute('required');
    } else {
        companySelect.disabled = false;
        companySelect.setAttribute('required', 'required');
    }
}

roleSelect.addEventListener('change', updateCompanyField);

updateCompanyField();
</script>

</body>
</html>
