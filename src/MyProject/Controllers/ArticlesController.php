<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;
//use MyProject\View\View;

class ArticlesController extends AbstractController     //Публичные методы контроллера ещё называются action-ами (от англ. action - действие).
{

    /*public function view(){
          $article = Article::getById($articleId);

          if ($article === null){
              throw new NotFoundException();
          }

          $this->view->renderHtml('articles/view.php', ['article' => $article]);
      }*/
    public function edit(int $articleId): void // обновлене данных в БД
    {
        $article = Article::getById($articleId);

        if($article == null){
            throw new NotFoundException();
        }

        if($this->user === null){
            throw new UnauthorizedException();
        }

        if(!$this->user->isAdmin()){
            throw new ForbiddenException();
        }

        if(!empty($_POST)){
            try{
                $article->updateFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e){
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function add(): void // добавление статьи в БД
    {
        if($this->user === null){
            throw new UnauthorizedException();
        }

        if(!$this->user->IsAdmin()){
            throw new ForbiddenException();
        }

        if (!empty($_POST)){
            try{
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e){
               $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
               return;
            }
            header('Location: /articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/add.php');

    }

    public function del(int $articleId): void // Удаление статьи из БД
    {
        $article = Article::getById($articleId);

        if($article !== null) {
            $article->deleteArticles();
            header('Location: /', true, 302);
        } else {
        throw new NotFoundException();
        }
    }
}