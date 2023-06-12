<?php
/**
 * @var array $categories
 * @var array $category_ids
 */?>
<?php if (!empty($article->error)): ?>
    <ul>
        <?php foreach ($article->error as $fault): ?>
            <li><?= $fault; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>


<form method="post" id="formArticle">

    <div>
        <label for="title">Title</label><input name="title" id="title" placeholder="Article title"
                                               value="<?= htmlspecialchars($article->title); ?>">
    </div>

    <div>
        <label for="content">Content</label><textarea name="content" rows="4" cols="40" id="content"
                                                      placeholder="Article content"><?= htmlspecialchars($article->content); ?></textarea>
    </div>

    <div>
        <label for="publisched_at">Publication date and time</label><input type="datetime" name="publisched_at"
                                                                           id="publisched_at"
                                                                           placeholder="YYYY-mm-dd HH:ii:ss"
                                                                           value="<?= htmlspecialchars($article->publisched_at); ?>">
    </div>

    <fieldset>
        <legend>Categories</legend>

        <?php foreach ($categories as $category) : ?>
            <div>
                <input type="checkbox" name="category[]" value="<?= $category['id'] ?>"
                       id="category<?= $category['id'] ?>"
                       <?php if (in_array($category['id'], $category_ids)) : ?>checked<?php endif; ?>>
                <label for="category<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></label>
            </div>
        <?php endforeach; ?>
    </fieldset>

    <button>Save</button>

</form>
