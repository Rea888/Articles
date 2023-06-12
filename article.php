<?php

require 'includes/init.php';

$conn = require 'includes/db.php';

if (isset($_GET['id'])) {

    $article = Article::getWithCategories($conn, $_GET['id'], true);

} else {
    $article = null;
}

?>
<?php require 'includes/header.php'; ?>

<?php if ($article): ?>
    <article>
        <h2><?= htmlspecialchars($article->title); ?></h2>

        <time datetime="<?= $article->publisched_at ?>"><?php $dateTime = new DateTime($article->publisched_at);
            echo $dateTime->format("j F, Y"); ?></time>

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
<?php else: ?>
    <p>Article not found.</p>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
