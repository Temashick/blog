<?php
 namespace MyProject\Controllers;

 use MyProject\Exceptions\DbException;
 use MyProject\Exceptions\ForbiddenException;
 use MyProject\Exceptions\UnauthorizedException;
 use MyProject\Exceptions\WriteException;
 use MyProject\Models\Articles\Article;
 use MyProject\View\View;
 use MyProject\Services\UsersAuthService;
 use MyProject\Models\Comments\Comment;

class AdminController
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

     public function view()
     {

         if ($this->user === null){
             throw new UnauthorizedException();
         }

         if (!$this->user->IsAdmin()){
             throw new ForbiddenException();
         }

         $this->view->renderHtml('admin/adminpanel.php');
     }

     public function viewLatestComments()
     {
         if ($this->user === null){
             throw new UnauthorizedException();
         }

         if (!$this->user->IsAdmin()){
             throw new ForbiddenException();
         }

         $comments = Comment::latestData();
         if($comments === null){
             throw new DbException('Запрос не выполнен');
         }

         $this->view->renderHtml('admin/listOfRecentComments.php', ['comments' => $comments]);
     }

    public function viewLatestArticles()
    {
        if ($this->user === null){
            throw new UnauthorizedException();
        }

        if (!$this->user->IsAdmin()){
            throw new ForbiddenException();
        }

        $articles = Article::latestData();

        if($articles === null){
            throw new DbException('Запрос не выполнен');
        }

        $this->view->renderHtml('admin/listOfRecentArticles.php', ['articles' => $articles]);
    }

    public function changeNameSite() // изменение названия сайта
    {
        if ($this->user === null){
            throw new UnauthorizedException();
        }

        if (!$this->user->IsAdmin()){
            throw new ForbiddenException();
        }
        try {
        if(empty($_POST['namesite'])){
            throw new WriteException('Не удалось переименовать');
        }



            $fd = fopen('E:/OSPanel/domains/myproject.loc/src/site', 'w');

            if(!$fd){
                throw new WriteException('Нет доступа');
            }
            fwrite($fd, $_POST['namesite']);
            fclose($fd);
            header('Location: /adminpanel', 302);

    }  catch
        (WriteException $e){
            $this->view->renderHtml('admin/adminpanel.php', ['error' => $e->getMessage()]);
            return;
        }
    }

 }