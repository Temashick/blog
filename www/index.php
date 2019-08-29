<?php

require __DIR__. '/../vendor/autoload.php';

try {
    //Теперь за автозагрузку функций отвечает код, сгенерированный composer
    /*spl_autoload_register(function (string $className) {             // В функцию spl_autoload_register можно и вовсе передать не имя функции, а прямо саму функцию.
        require_once __DIR__ . '/../src/' . $className . '.php';    // В таком случае, функция называется анонимной – у неё нет имени.
    });        */                                                     // Она просто передаётся в качестве аргумента и имя ей не нужно.

    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/../src/routes.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) { // если нет нужного роута, то бросаем исключение
        throw new \MyProject\Exceptions\NotFoundException();
    }
    // нужные нам аргументы всегда будут только после нулевого элемента, так как в нём лежит полное совпадение по паттерну.
    unset($matches[0]);     // просто удаляем этот ненужный элемент

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();    // специальный оператор троеточия -
    $controller->$actionName(...$matches);  // Он передаст элементы массива в качестве аргументов методу в том порядке, в котором они находятся в массиве.
}catch (\MyProject\Exceptions\DbException $e){
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\MyProject\Exceptions\NotFoundException $e){
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\MyProject\Exceptions\UnauthorizedException $e){
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch (\MyProject\Exceptions\ForbiddenException $e){
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage(), 'user' => \MyProject\Services\UsersAuthService::getUserByToken()], 403);
} catch (\MyProject\Exceptions\NotDeleted $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('304.php', ['error' => $e->getMessage()], 304);
} catch (\MyProject\Exceptions\NoContentException $e) {
    $view = new \MyProject\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('noContent.php', ['error' => $e->getMessage()], 204);
}


/*
if (!empty($matches)){
    $controller = new \MyProject\Controllers\MainController();
    $controller->sayHello($matches[1]);
    return;
}

$pattern = '~^$~';
preg_match($pattern, $route, $matches);

if (!empty($matches)){
    $controller = new \MyProject\Controllers\MainController();
    $controller->main();
    return;
}

echo 'Страница не найдена';

$controller = new \MyProject\Controllers\MainController();
if (!empty($_GET['name'])){
    $controller->sayHello($_GET['name']);
} else {
    $controller->main();
}
//.htaccess
/*RewriteEngine On – включаем режим перенаправления запросов
RewriteCond %{SCRIPT_FILENAME} !-d – если в директории есть папка, соответствующая адресу запроса, то отдать её в ответе
RewriteCond %{SCRIPT_FILENAME} !-f – если в директории есть файл, соответствующий адресу запроса, то вернуть его в ответе
RewriteRule ^(.)$ ./index.php?route=$1 [QSA,L] – если файл или папка не найдены, то для такого запроса выполнится этот пункт.
 *В таком случае веб-сервер перенаправить этот запрос на скрипт index.php.
 * При этом скрипту будет передан GET-параметр route со значением запрошенного адреса.
 * $1 – это значение, выдернутое с помощью регулярки по маске ^(.)$. То есть вся адресная строка будет передана в этот GET-параметр.
 * */