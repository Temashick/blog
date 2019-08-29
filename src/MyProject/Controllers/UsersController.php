<?php
namespace MyProject\Controllers;

use MyProject\Exceptions\ForbiddenException;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\UnauthorizedException;
use MyProject\Models\Users\UserActivationService;
use MyProject\Services\EmailSender;
use MyProject\Services\UsersAuthService;
use MyProject\Models\Users\User;
use MyProject\Exceptions\ActivateException;
use MyProject\Exceptions\NoContentException;

class UsersController extends AbstractController
{

    public function viewPersonalAccount()      // отображение личного кабинета
    {
        if ($this->user === null){
            throw new UnauthorizedException();
        }

        $this->view->renderHtml('users/personalAccount.php');
    }

    public function signUp()
    {
        if (!empty($_POST)){
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }
        }

        if($user instanceof User) {
            $code = UserActivationService::createActivationCode($user);

            EmailSender::send($user, 'Активация', 'userActivation.php',[
                'userId' => $user->getId(),
                'code' => $code
                ]
            );

            $this->view->renderHtml('users/signUpSuccessful.php');
            return;
        }

        $this->view->renderHtml('users/signUp.php');
    }

    public function activate(int $userId, string $activationCode) // активация аккаунта
    {
        try{
        $user = User::getById($userId);

        if($user === null) {
            throw new ActivateException('Такой пользователь не найден');
        }

        if ($user->getIsConfirmed() == 1){
            throw new ActivateException('Пользователь уже активирован');
        }

        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);
        if ($isCodeValid) {
            $user->activate();
            UserActivationService::deleteCode($userId);
            $this->view->renderHtml('users/activationSuccessful.php');
        } else{
            throw new ActivateException('Неверный код активации');
        }
        } catch (ActivateException $e){
            $this->view->renderHtml('errors/activationError.php', ['error' => $e->getMessage()]);
            return;
        }
    }

    public function login() // авторизация пользователя
    {
        if(!empty($_POST)){
            try{
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /');
                exit();
            }catch (InvalidArgumentException $e){
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }
        $this->view->renderHtml('users/login.php');
    }

    public function logout() // разлогинивание пользователя
    {
        setcookie('token', time()-1);
        header('Location: /');
    }


    public function del(): void // Удаление пользователя
    {

        if($this->user === null){
            throw new UnauthorizedException();
        }

        $this->user->delUser();

    }

    public function updateNickname()
    {

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_POST)) {
            try {
                $this->user->updateNickname($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/personalAccount.php', ['error1' => $e->getMessage()]);
                return;
            }
            header('Location: /personalaccount', true, 302);
            exit();
        }
    }
        public function updatePassword()
    {
        if($this->user === null){
            throw new UnauthorizedException();
        }

        if(!empty($_POST)) {
            try {
                $this->user->updatePassword($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/personalAccount.php', ['error2' => $e->getMessage()]);
                return;
            }
            header('Location: /personalaccount', true, 302);
            exit();
        }
    }

    /*public function downloadImage()
    {
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!empty($_FILES['attachment'])) {
            try {
                $this->user->updateImage($_FILES['attachment']);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/personalAccount.php', ['error3' => $e->getMessage()]);
                return;
            }
            header('Location: /personalaccount', true, 302);
            exit();
        }
    }*/

   /*
   public function viewImage()
    {
        $imagePath = 'image/' . $this->user->getId() . '.jpg';
        $this->view->renderHtml($imagePath);
    }
   */
}
