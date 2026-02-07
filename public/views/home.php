<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH MEDIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/public/styles/base.css">
    <link rel="stylesheet" href="/public/styles/layout.css">
    <link rel="stylesheet" href="/public/styles/components.css">
    <link rel="stylesheet" href="/public/styles/header.css">
    <link rel="stylesheet" href="/public/styles/pages/home.css">
</head>
<body class="home-page">

<?php require __DIR__ . '/partials/header.php'; ?>

<section class="hero">
    <video class="bg-video" autoplay muted loop playsinline>
        <source src="/public/videos/hero.mp4" type="video/mp4">
    </video>

    <div class="hero-overlay"></div>

    <div class="hero-content">
        <img src="/public/images/logo.png" alt="FINCH MEDIA" class="hero-logo">
        <h1>FINCH MEDIA</h1>
    </div>
</section>

</body>
</html>
