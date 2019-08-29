</td>
<td width="300px" class="sidebar">
    <div class="sidebarHeader">Меню</div>
    <ul>
        <li><a href="/">Главная страница</a></li>
        <? if(!empty($user)): ?>
            <li><a href="/personalaccount">Личный кабинет</a></li>
        <? if($user->isAdmin()): ?>
        <li><a href="/adminpanel">Панель администратора</a></li>
        <? endif; ?>
        <? endif; ?>
        <li><a href="/about-me">Обо мне</a></li>
    </ul>
</td>
</tr>
<tr>
    <td class="footer" colspan="3">Все права защищены (с) Мой блог</td>
</tr>
</table>
</body>
</html>
