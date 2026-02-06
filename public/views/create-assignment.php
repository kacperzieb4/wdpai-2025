<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Create assignment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/public/styles/header.css">
    <link rel="stylesheet" href="/public/styles/dashboard.css">
    <link rel="stylesheet" href="/public/styles/create-user.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="content">

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

            <button type="submit" class="btn-primary">
                Create assignment
            </button>

        </form>

    </div>

</main>

</body>
</html>

<script>
const form = document.querySelector('form');
const uploadBox = document.getElementById('uploadBox');
const progressBar = document.getElementById('uploadProgress');
const percentText = document.getElementById('uploadPercent');

form.addEventListener('submit', function (e) {
    const fileInput = form.querySelector('input[type="file"]');

    if (!fileInput || !fileInput.files.length) {
        return;
    }

    e.preventDefault();

    const xhr = new XMLHttpRequest();
    const formData = new FormData(form);

    uploadBox.style.display = 'block';

    xhr.upload.addEventListener('progress', function (e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.style.width = percent + '%';
            percentText.textContent = percent + '%';
        }
    });

    xhr.addEventListener('load', function () {
        if (xhr.status === 200) {
            window.location.href = '/assignments';
        } else {
            alert('Upload failed');
        }
    });

    xhr.open('POST', window.location.href);
    xhr.send(formData);
});
</script>
