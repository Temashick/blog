<?php include __DIR__ . '/../header.php'; ?>
<? if (!empty($error)): ?>
    <div style="background-color: red; padding: 5px; margin: 15px"><?= $error?></div>
<? endif; ?>
Список последних статей. <a href="/adminpanel/latestarticles">Перейти</a>
<br><br>
Список последних комментариев. <a href="/adminpanel/latestсomments">Перейти</a>
<br><br>
<a href="articles/add">Добавить</a> статью.
<br><br>
<form action="/change" method="post">
    <input type="text" name="namesite" placeholder="Введите название сайта "> <input type="submit" value="Изменить">
</form>
<?php include __DIR__ . '/../footer.php'; ?>
