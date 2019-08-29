<?php include __DIR__ . '/../header.php'; ?>
<?php if(!empty($error1)): ?>
<div style="color: red;"><?= $error1 ?></div>
<?php endif; ?>
<br>
<form action="/user/changenickname" method="post">
    <label>Nickname <input type="text" name="nickname" placeholder="Введите новый ник" value="<?= $_POST['nickname'] ?? '' ?>" ></label>
    <br><br>
    <input type="submit" value="Изменить">
</form>
<br><br>
<?php if(!empty($error2)): ?>
    <div style="color: red;"><?= $error2 ?></div>
<?php endif; ?>
<br>
<form action="/user/changepassword" method="post">
    <label>Password <input type="password" name="password1" placeholder="Введите новый пароль"></label>
    <br><br>
    <label>Repeat <input type="password" name="password2" placeholder="Повторите" ></label>
    <br><br>
    <input type="submit" value="Изменить">
</form>
<br>
<a href="personalaccount/delete" onclick="return confirm('Вы действительно хотите удалить аккаунт?') ? true : false;">Удалить аккаунт</a>
<td colspan="1">
    <?php if(!empty($error3)): ?>
        <div style="color: red;"><?= $error3 ?></div>
    <?php endif; ?>
    <form action="users/upload" method="post" enctype="multipart/form-data">
        <input type="file" name="attachment">
        <input type="submit">
    </form>
    <br>
    <? $image = "<img src='user/10'  alt='Аватарка'>"; ?>
    <a href="user/image/<?= $user->getId() ?>"><img src='user/10'  alt='Аватарка'></a>
</td>
<?php include __DIR__ . '/../footer.php'; ?>
