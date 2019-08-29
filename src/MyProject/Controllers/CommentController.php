<?php

namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Exceptions\NotFoundException;
use MyProject\Models\Articles\Article;
use MyProject\Models\Comments\Comment;
use MyProject\Services\UsersAuthService;
use MyProject\Models\Users\User;

class CommentController extends AbstractController     //Публичные методы контроллера ещё называются action-ами (от англ. action - действие).
{
    /*public function view()
    {
        $comments = Comment::findAll();
        $this->view->renderHtml('articles/view.php', ['comments' => $comments]);
    }
    */

    public function edit(int $commentId): void // обновление данных в БД
    {
        $comment = Comment::getById($commentId);
        if($comment == null){
            throw new NotFoundException();
        }

        if($this->user === null){
            throw new UnauthorizedException();
        }

        if((!$this->user->isAdmin()) && ($this->user->getId() !== $comment->getUserId()) ){
            throw new ForbiddenException();
        }

        if(!empty($_POST)){
            try{
                $comment->updateFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e){
                $this->view->renderHtml('comments/edit.php', ['error' => $e->getMessage(), 'comment' => $comment]);
                return;
            }
            header('Location: /articles/' . $comment->getArticleId(), true, 302);
            exit();
        }

        $this->view->renderHtml('comments/edit.php', ['comment' => $comment]);
    }

        public function add($articleId): void // добавление комментария в БД
        {
            if($this->user === null){
                throw new UnauthorizedException();
            }
            $article = Article::getById($articleId);

            if ($article === null){
                throw new NotFoundException();
            }

            $comments = Comment::findAll();

            if (!empty($_POST)){
                try{
                    $comment = Comment::createFromArray($_POST, $this->user, $article->getId());
                } catch (InvalidArgumentException $e){
                    $this->view->renderHtml('articles/view.php', ['error' => $e->getMessage(), 'article' => $article,'comments' => $comments]);
                    return;
                }
                header('Location: /articles/' . $comment->getArticleId().'#comment'.$comment->getId(), true, 302);
                exit();
            }

            $this->view->renderHtml('articles/view.php');

        }


        public function del(int $commentId): void // Удаление комментария из БД
        {
            $comment = Comment::getById($commentId);

            if($comment == null){
                throw new NotFoundException();
            }

            if($this->user === null){
                throw new UnauthorizedException();
            }

            if((!$this->user->isAdmin()) && ($this->user->getId() !== $comment->getUserId()) ){
                throw new ForbiddenException();
            }

            if($comment !== null) {
                $comment->delComment();
            } else {
                throw new NotFoundException();
            }
        }
}
