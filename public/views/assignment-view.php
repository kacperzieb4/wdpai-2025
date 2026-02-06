<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($assignment['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/public/styles/assignment.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="assignment-wrapper">

    <h1 class="file-title"><?= htmlspecialchars($assignment['title']) ?></h1>

    <div class="video-box">
        <video id="assignmentVideo" controls>
            <source src="/<?= htmlspecialchars($assignment['video_path']) ?>" type="video/mp4">
        </video>
    </div>

    <div class="download-box">
        <a href="/<?= htmlspecialchars($assignment['video_path']) ?>" download class="download-btn">
            ⬇ Download
        </a>
    </div>

    <?php if (!empty($assignment['description'])): ?>
        <h3 class="section-title">Description:</h3>
        <div class="assignment-description">
            <?= nl2br(htmlspecialchars($assignment['description'])) ?>
        </div>
    <?php endif; ?>

    <h3 class="section-title">New Comment:</h3>

    <div class="comment new-comment">
        <form method="POST" action="/add-comment">
            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
            <input type="hidden" name="video_timestamp" id="timestampInput">

            <label class="timestamp-toggle">
                <input type="checkbox" id="withTimestamp"> With timestamp
            </label>

            <div class="comment-row">
                <input type="text"
                       name="content"
                       class="comment-input"
                       placeholder="Your comment"
                       required>
            </div>

            <button type="submit" class="comment-btn">Add comment</button>
        </form>
    </div>

    <h3 class="section-title">Comments:</h3>

    <?php foreach ($comments as $comment): ?>
        <?php
            $isOwner   = $comment['user_id'] == $_SESSION['user_id'];
            $isAdmin   = $_SESSION['user_role'] === 'ADMIN';
            $isMod     = $_SESSION['user_role'] === 'MODERATOR';

            $canEdit   = $isOwner;
            $canDelete = $isOwner || $isAdmin || $isMod;
        ?>

        <div class="comment" data-comment-id="<?= $comment['id'] ?>">

            <div class="comment-header">
                <strong><?= htmlspecialchars($comment['user_name']) ?></strong>
                <small><?= date('Y-m-d H:i:s', strtotime($comment['created_at'])) ?></small>
            </div>

            <?php if ($comment['video_timestamp'] !== null): ?>
                <div class="comment-timestamp clickable"
                     data-seconds="<?= (int)$comment['video_timestamp'] ?>">
                    ⏱ <?= gmdate('i:s', $comment['video_timestamp']) ?>
                </div>
            <?php endif; ?>

            <p class="comment-content">
                <?= htmlspecialchars($comment['content']) ?>
            </p>

            <?php if ($canEdit || $canDelete): ?>
                <div class="comment-actions">
                    <?php if ($canEdit): ?>
                        <span class="comment-edit">Edit</span>
                    <?php endif; ?>

                    <?php if ($canDelete): ?>
                        <a href="/delete-comment/<?= $comment['id'] ?>">Delete</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($canEdit): ?>
                <div class="comment-edit-box">
                    <input
                        type="text"
                        class="comment-input comment-edit-input"
                        value="<?= htmlspecialchars($comment['content']) ?>"
                    >
                    <div class="comment-edit-actions">
                        <button class="comment-btn save-comment">Save</button>
                        <button class="comment-btn cancel-comment">Cancel</button>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</main>

<script src="/public/scripts/assignment.js"></script>

<script>
document.querySelectorAll('.comment-edit').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.comment').classList.add('editing');
    });
});

document.querySelectorAll('.cancel-comment').forEach(btn => {
    btn.addEventListener('click', () => {
        btn.closest('.comment').classList.remove('editing');
    });
});

document.querySelectorAll('.save-comment').forEach(btn => {
    btn.addEventListener('click', () => {
        const comment = btn.closest('.comment');
        const id = comment.dataset.commentId;
        const content = comment.querySelector('.comment-edit-input').value;

        fetch('/edit-comment/' + id, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'content=' + encodeURIComponent(content)
        }).then(() => location.reload());
    });
});
</script>

</body>
</html>
