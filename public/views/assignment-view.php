<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($assignment['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="main main--center">

    <h1 class="page-title"><?= htmlspecialchars($assignment['title']) ?></h1>

    <video id="assignmentVideo"
           controls
           style="
               width:100%;
               max-height:70vh;
               border-radius:12px;
               background:#000;
           ">
        <source src="/<?= htmlspecialchars($assignment['video_path']) ?>" type="video/mp4">
    </video>

    <div style="margin: 20px 0;">
        <a href="/<?= htmlspecialchars($assignment['video_path']) ?>"
           download
           class="btn btn--primary btn--block"
           style="display:block; text-align:center;">
            ⬇ Download
        </a>
    </div>

    <?php if (!empty($assignment['description'])): ?>
        <h3 class="section-title">Description:</h3>
        <div class="card">
            <?= nl2br(htmlspecialchars($assignment['description'])) ?>
        </div>
    <?php endif; ?>

    <h3 class="section-title">New comment</h3>

    <div class="card">
        <form method="POST" action="/add-comment">
            <input type="hidden" name="assignment_id" value="<?= $assignment['id'] ?>">
            <input type="hidden" name="video_timestamp" id="timestampInput">

            <label>
                <input type="checkbox" id="withTimestamp">
                With timestamp
            </label>

            <input
                type="text"
                name="content"
                placeholder="Your comment"
                required
            >

            <button type="submit" class="btn">
                Add comment
            </button>
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

        <div class="card comment" data-comment-id="<?= $comment['id'] ?>">

            <div class="comment-header">
                <div class="comment-author">
                    <strong><?= htmlspecialchars($comment['user_name']) ?></strong><br>
                    <small><?= date('Y-m-d H:i:s', strtotime($comment['created_at'])) ?></small>
                </div>

                <?php if ($canEdit || $canDelete): ?>
                    <div class="action-links">
                        <?php if ($canEdit): ?>
                            <span class="action-link comment-edit">Edit</span>
                        <?php endif; ?>

                        <?php if ($canDelete): ?>
                            <a href="/delete-comment/<?= $comment['id'] ?>"
                               class="action-link action-link--danger">
                                Delete
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($comment['video_timestamp'] !== null): ?>
                <div class="action-link comment-timestamp"
                     style="margin:8px 0;"
                     data-seconds="<?= (int)$comment['video_timestamp'] ?>">
                    ⏱ <?= gmdate('i:s', $comment['video_timestamp']) ?>
                </div>
            <?php endif; ?>

            <p><?= htmlspecialchars($comment['content']) ?></p>

            <?php if ($canEdit): ?>
                <div class="comment-edit-box">
                    <input
                        type="text"
                        class="comment-edit-input"
                        value="<?= htmlspecialchars($comment['content']) ?>"
                    >

                    <div style="margin-top:10px; display:flex; gap:10px;">
                        <button class="btn save-comment">Save</button>
                        <button class="btn cancel-comment">Cancel</button>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

    <div style="height:40px;"></div>

</main>

<script src="/public/scripts/assignment.js"></script>
<script src="/public/scripts/assignment-comments.js"></script>

</body>
</html>
