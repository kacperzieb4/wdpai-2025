<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Edit assignment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title">Edit assignment</h1>

    <div class="card">

        <form method="POST"
              action="/edit-assignment/<?= $assignment['id'] ?>"
              enctype="multipart/form-data">

            <input
                type="text"
                name="title"
                value="<?= htmlspecialchars($assignment['title']) ?>"
                placeholder="Assignment title"
                required
            >

            <textarea
                name="description"
                placeholder="Assignment description (optional)"
                rows="4"
            ><?= htmlspecialchars($assignment['description'] ?? '') ?></textarea>

            <div class="file-info">
                <strong>Current video:</strong>
                <span class="file-name">
                    <?= htmlspecialchars(basename($assignment['video_path'])) ?>
                </span>
            </div>

            <input
                type="file"
                name="video"
                accept="video/*"
            >

            <div id="uploadBox" class="upload-box" style="display:none;">
                <div class="upload-bar">
                    <div id="uploadProgress" class="upload-progress"></div>
                </div>
                <div id="uploadPercent" class="upload-percent">0%</div>
            </div>

            <select name="company_id" required>
                <?php foreach ($companies as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= $c['id'] == $assignment['company_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn--primary btn--block">
                Save changes
            </button>

        </form>

    </div>

</main>

<script src="/public/scripts/upload.js"></script>

</body>
</html>
