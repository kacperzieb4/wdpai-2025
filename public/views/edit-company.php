<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edit company</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Edit company</h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="card" style="background:#ffdddd;">
            <?= htmlspecialchars($errorMessage) ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST">

            <input
                type="text"
                name="name"
                value="<?= htmlspecialchars($company['name']) ?>"
                placeholder="Company name"
                required
            >

            <button type="submit" class="btn btn--primary btn--block">
                Save changes
            </button>

        </form>
    </div>

</main>

</body>
</html>
