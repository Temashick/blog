<?php include __DIR__ . '/../header.php'; ?>
<p><a name="top"></a></p>
    <h1><?= $article->getName() ?></h1>
    <p><?= $article->getParsedText() ?></p>
<h4 style="text-align: right;"> Автор:  <?= $article->getAuthor()->getNickname() ?? 'Удалён' ?></h4>
<h4 style="text-align: right;" > Опубликовано: <?= $article->getCreatedAt() ?></h4>
<? if(!empty($user) && ($user->isAdmin())): ?>
    <hr>
    <div style="text-align: right;"><a href="/articles/<?= $article->getId()?>/edit">Редактировать</a> статью | <a href="/articles/<?= $article->getId()?>/delete">Удалить</a></div>
<? endif; ?>
            <hr size="2" color="#555555">
            <h2 style="float: left; width: 30%;">Комментарии</h2>
            <? if(!empty($user)): ?>
                <br>
                <form style="text-align: center;" action="/articles/<?= $article->getId() ?>/comment" method="post">
                    <textarea name="text" id="textcomment" rows="10" cols="80"><?= $_POST['textcomment'] /*?? $article->getText() */?></textarea><br>
                    <input type="submit" value="Отправить">
                </form>
            <? else: ?>
                <p style="text-align: right;">Нужно авторизоваться для добавления комментария.</p>
                <br>
            <? endif; ?>
            <div style="text-align: left;">
            <?php foreach ($comments as $comment): ?>
            <? if($article->getId() == $comment->getArticleId()): ?>
                <a name="comment<?= $comment->getId() ?>"></a>
                <h3><?= $comment->getAuthor()->getNickname()?></h3>
                <p><?= $comment->getText() ?></p>
                <? if((!empty($user)) && (!empty($comment))): ?>
                <? if(($comment->getAuthor()->getNickname() === $user->getNickname()) || ($user->isAdmin())): ?>
                            <a href="/articles/comment/<?= $comment->getId()?>/edit">Редактировать</a> |
                            <a href="/articles/comment/<?= $comment->getId() ?>/delete">Удалить</a>
                <? endif; ?>
                <? endif; ?>
                    <div style="text-align: right;" >Добавлен: <?= $comment->getcreatedAt() ?></div>
                <hr>
            <? endif; ?>
            <?php endforeach; ?>
            </div>
            <p><a href="#top">Наверх</a></p><p><a name="down"><a></p>
<?php include __DIR__ . '/../footer.php'; ?>
