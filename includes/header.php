<!DOCTYPE html>
<html>
<head>
    <title>My blog</title>
    <meta charset="utf-8">
</head>
<body>

<header>
    <h1>My blog</h1>
</header>

<nav>
    <ul>
        <li><a href="/article/articles_main.php">Home</a></li>
        <?php if (Auth::isLoggedIn()) : ?>

            <li><a href="/article/admin/articles_main.php">Admin</a></li>
            <li><a href="/article/logout.php">Log out</a></li>
        <?php else : ?>

            <li><a href="/article/login.php">Log in</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
