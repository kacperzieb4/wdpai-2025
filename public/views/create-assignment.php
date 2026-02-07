<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Create assignment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Create assignment</h1>

    <div class="card">

        <form method="POST" action="/create-assignment" enctype="multipart/form-data">

            <input
                type="text"
                name="title"
                placeholder="Assignment title"
                required
            >

            <textarea
                name="description"
                placeholder="Assignment description (optional)"
                rows="4"
            ></textarea>


            <input
                type="file"
                name="video"
                accept="video/*"
                required
            >

            <div id="uploadBox" class="upload-box" style="display:none;">
                <div class="upload-bar">
                    <div id="uploadProgress" class="upload-progress"></div>
                </div>
                <div id="uploadPercent" class="upload-percent">0%</div>
            </div>


            <select name="company_id" required>
                <option value="" disabled selected>
                    Select company
                </option>
                <?php foreach ($companies as $c): ?>
                    <option value="<?= $c['id'] ?>">
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn--primary">
                Create assignment
            </button>

        </form>

    </div>

</main>

<script src="/public/scripts/upload.js"></script>

</body>
</html>
