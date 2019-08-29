<?php
/**
 * @var \MyProject\Models\Comments\Comment $comment
 */
include __DIR__ . '/../header.php';
?>
<h1>Редактирование комментария</h1>
<?php if(!empty($error)): ?>
    <div style="color: red;"><?= $error ?></div>
<?php endif; ?>
<form action="/articles/comment/<?= $comment->getId() ?>/edit" method="post">
<label for="text">Текст комментария</label><br>
<textarea name="text" id="text" rows="10" cols="80"><?= $comment->getText() ?></textarea><br>
<br>
<input type="submit" value="Обновить">
</form>
<?php include __DIR__ . '/../footer.php'; ?>
