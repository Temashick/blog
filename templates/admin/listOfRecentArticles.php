<?php include __DIR__ . '/../header.php'; ?>
<h1 style="text-align: center;">Последние опубликованные статьи</h1>
<hr>
<?php foreach ($articles as $article): ?>
<h2><a href="/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h2>
<p><?= $article->getShortText()?></p>
<h4><?= $article->getAuthor()->getNickname(); ?><h4>
        <? if(!empty($user) && ($user->isAdmin())): ?>
        <hr>
        <div style="text-align: right;"><a href="/articles/<?= $article->getId()?>/edit">Редактировать</a> статью | <a href="/articles/<?= $article->getId()?>/delete">Удалить</a></div>
                <? endif; ?>
        <hr size="3px" color="#002321">
        <?php endforeach; ?>
<?php include __DIR__ . '/../footer.php'; ?>
