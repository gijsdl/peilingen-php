<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="css/style.css" rel="stylesheet">
    <title>Document</title>
    <script src="js/message.js" defer></script>
</head>
<body class="bg-info-subtle">
<nav class="navbar navbar-expand-lg bg-primary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link text-light" href="/">Home</a>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a class="nav-link text-light" href="/login">Login</a>
                <?php else: ?>
                    <a class="nav-link text-light" href="/new-poll">Peiling toevoegen</a>
                    <a class="nav-link text-light" href="/make-user">Gebruiker aanmaken</a>
                    <a class="nav-link text-light" href="/logout">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="container mb-5">
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $flash): ?>
            <div class="row">
                <div class="col mb-3 message">
                    <div class="alert alert-<?= $flash['class'] ?> alert-dismissible" role="alert">
                        <?= $flash['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        <?php unset($_SESSION['flash']) ?>
    <?php endif; ?>
    <div class="row">
        <div class="col mb-3 message hidden">
            <div class="alert alert-dismissible" role="alert">
                <span>message</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>