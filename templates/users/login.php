<?php include __DIR__ .'/../header.php' ; ?>
<div style="text-align: center;">
    <h1>Вход</h1>
    <? if (!empty($error)): ?>
    <div style="background-color: red; padding: 5px; margin: 15px"><?= $error?></div>
    <? endif; ?>
    <form action="/users/login" method="post">
        <div>
        <label>Email <input type="text" name="email" value="<?= $_POST['email'] ?? '' ?>" ></label>
        <br><br>
        <label>Password <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>" ></label>
        <br><br>
        <input type="submit" value="Войти">
        </div>
    </form>
</div>
<? include __DIR__ .'/../footer.php'; ?>
