<?php include __DIR__ . '/../header.php'; ?>
<h1 style="text-align: center;">Последние комментарии</h1>
<hr>
<?php foreach ($comments as $comment): ?>
        <a name="comment<?= $comment->getId() ?>"></a>
        <h3><?= $comment->getAuthor()->getNickname() ?></h3>
        <p><?= $comment->getText() ?></p>
        <? if((!empty($user)) && (!empty($comment))): ?>
            <? if(($comment->getAuthor()->getNickname() === $user->getNickname()) || ($user->isAdmin())): ?>
                <a href="/articles/comment/<?= $comment->getId()?>/edit">Редактировать</a> |
                <a href="/articles/comment/<?= $comment->getId() ?>/delete">Удалить</a>
                <div style="text-align: right;" >Добавлен: <?= $comment->getcreatedAt() ?></div>
            <? endif; ?>
        <? endif; ?>
        <hr>
<?php endforeach; ?>
<?php include __DIR__ . '/../footer.php'; ?>
