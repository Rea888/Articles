<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

if (isset($_GET['id'])) {

    $article = Article::getWithCategories($conn, $_GET['id']);

} else {
    $article = null;
}

?>
<?php require '../includes/header.php'; ?>

<?php if ($article): ?>
    <article>
        <h2><?= htmlspecialchars($article->title); ?></h2>

        <?php if ($article->publisched_at) : ?>
            <time><?= $article->publisched_at ?></time>
        <?php else : ?>
            Unpublished
        <?php endif; ?>

        <?php if ($article->category_name) : ?>
            <p>Categories:
                <?= htmlspecialchars($article->category_name); ?>
            </p>
        <?php endif; ?>

        <?php if ($article->image_file) : ?>
            <img src="/article/uploads/<?= $article->image_file; ?>">
        <?php endif; ?>

        <p><?= htmlspecialchars($article->content); ?></p>
    </article>

    <a href="edit_article.php?id=<?= $article->id; ?>">Edit</a>
    <a class="delete" href="delete_article.php?id=<?= $article->id; ?>">Delete</a>
    <a href="edit_article_image.php?id=<?= $article->id; ?>">Edit image</a>

<?php else: ?>
    <p>Article not found.</p>
<?php endif; ?>

<?php require '../includes/footer.php'; ?>
