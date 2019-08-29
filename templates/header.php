<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>
        <?
        $str = file_get_contents(__DIR__ . '/../src/site');

        if(!empty($str)){
            echo $str;
        }else{
            echo 'Мой блог';
        } ?>
    </title>
    <link rel="stylesheet" href="/style.css">
</head>
<body>
<table class="layout">
    <tr>
        <td colspan="3" class="header">
            <?
            if(!empty($str)){
              echo $str;
            }else{
            echo 'Мой блог';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="text-align: right">
            <? if(!empty($user)): ?>
                <?= 'Привет, ' . $user->getNickname()?> | <a href="/logout">Выйти</a>
            <?php else: ?>
                <a href="/users/login">Войти</a> | <a href="/users/register">Зарегистрироваться</a>
            <?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>