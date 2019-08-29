<?php include __DIR__ . '/../header.php'; ?>
<?php foreach ($articles as $article): ?>
    <h2><a href="/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h2>
    <p><?= $article->getParsedText() ?></p>
<h3><?= $article->getAuthor()->getNickname() ?></h3>
        <h4>Опубликовано: <?= $article->getCreatedAt()?></h4>
        <hr>
<?php endforeach; ?>
<?php include __DIR__ . '/../footer.php'; ?>
