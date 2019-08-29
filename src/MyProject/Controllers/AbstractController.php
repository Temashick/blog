<?php

namespace MyProject\Controllers;

use MyProject\Models\Users\User;
use MyProject\Services\UsersAuthService;
use MyProject\View\View;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Exceptions\NotFoundException;

abstract class AbstractController
{
    /** @var View*/
    protected $view;

    /** @var User|null */
    protected $user;

    public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
    }

    public function view(int $articleId) // вывод статьи и комментариев к статье
    {
        $article = Article::getById($articleId);

        if ($article === null){
            throw new NotFoundException();
        }

        $comments = Comment::findAll();

        $this->view->renderHtml('articles/view.php', ['comments' => $comments, 'article' => $article]);
    }

    protected function getInputData()
    {
        return json_decode(
            file_get_contents('php://input'),
            true
        );
    }


}