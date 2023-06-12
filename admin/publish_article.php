<?php

require '../includes/init.php';

Auth::requireLogin();

$conn = require '../includes/db.php';

$article = Article::getByID($conn, $_POST['id']);

$publisched_at = $article->publish($conn);

?>
<time><?= $publisched_at ?></time>
