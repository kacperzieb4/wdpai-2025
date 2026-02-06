<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>FINCH MEDIA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="public/styles/home.css">
    <link rel="stylesheet" href="public/styles/header.css">
</head>
<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<section class="hero">
    <video autoplay muted loop class="bg-video">
        <source src="public/videos/hero.mp4" type="video/mp4">
    </video>

    <div class="overlay"></div>

    <div class="hero-content">
        <img src="public/images/logo.png" alt="FINCH" class="hero-logo">
        <p>FINCH MEDIA</p>
    </div>
</section>

</body>
</html>
