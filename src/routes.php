<?php

/*return [
  '~^bye/(.*)$~' => [\MyProject\controllers\MainController::class, 'sayBye'],
  '~^hello/(.*)$~' => [\MyProject\controllers\MainController::class, 'sayHello'],
  '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
];*/

return[
    '~^change$~' => [\MyProject\Controllers\AdminController::class, 'changeNameSite'],
    '~^adminpanel/latestсomments$~' => [\MyProject\Controllers\AdminController::class, 'viewLatestComments'],
    '~^adminpanel/latestarticles$~' => [\MyProject\Controllers\AdminController::class, 'viewLatestArticles'],
    '~^adminpanel$~' => [\MyProject\Controllers\AdminController::class, 'view'],
    '~^articles/comment/(\d+)/edit$~' => [\MyProject\Controllers\CommentController::class ,'edit'],
    '~^articles/(\d+)/comment$~' => [\MyProject\Controllers\CommentController::class, 'add'],
    '~^articles/comment/(\d+)/delete$~' => [\MyProject\Controllers\CommentController::class, 'del'],
    '~^articles/(\d+)$~' => [\MyProject\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\MyProject\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\MyProject\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)/delete$~' => [\MyProject\Controllers\ArticlesController::class, 'del'],
    '~^users/register$~' => [\MyProject\Controllers\UsersController::class, 'signUp'],
    '~^personalaccount/delete$~' => [\MyProject\Controllers\UsersController::class, 'del'],
    '~^users/(\d+)/activate/(.+)$~' => [\MyProject\Controllers\UsersController::class, 'activate'],
    '~^users/login$~' => [\MyProject\Controllers\UsersController::class, 'login'],
    '~^logout$~' => [\MyProject\Controllers\UsersController::class, 'logout'],
    '~^personalaccount$~' => [\MyProject\Controllers\UsersController::class, 'viewPersonalAccount'],
    '~^user/changenickname$~' => [\MyProject\Controllers\UsersController::class, 'updateNickname'],
    '~^user/changepassword$~' => [\MyProject\Controllers\UsersController::class, 'updatePassword'],
    /*'~^user/image/(\d+)$~' => [\MyProject\Controllers\UsersController::class, 'viewImage'],*/
    '~^$~' => [\MyProject\Controllers\MainController::class, 'main'],
];