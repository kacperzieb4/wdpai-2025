<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Create company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="stylesheet" href="/public/styles/create-user.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="content">

    <h1 class="page-title">Create company</h1>

    <div class="card">
        <form method="POST">
            <input
                type="text"
                name="name"
                placeholder="Company name"
                required
            >

            <button type="submit" class="btn-primary">
                Create company
            </button>
        </form>

        <?php if (!empty($errorMessage)): ?>
            <p class="error-message">
                <?= htmlspecialchars($errorMessage) ?>
            </p>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
