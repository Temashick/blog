<?php

namespace MyProject\Models\Users;

use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Exceptions\NoContentException;
use MyProject\Models\ActiveRecordEntity;

class User extends ActiveRecordEntity
{

    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var int */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;

    /** @var string */
    protected $createdAt;

    protected $image;

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname)
    {
        $this->nickname = $nickname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    protected static function getTableName(): string
    {
        return 'users';
    }

    public function getIsConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    public function IsAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    public function setPasswordHash(string $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public static function signUp(array $userData): User
    {
        if (empty($userData['nickname'])) {
            throw new InvalidArgumentException('Не передан nickname');
        }

        if (!preg_match('/[a-zA-Z0-9]+/', $userData['nickname'])) {
            throw new InvalidArgumnetException('Nickname может состоять только из символов латинского алфавита и цифр');
        }

        if (empty($userData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email неккорректен');
        }

        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        if (mb_strlen($userData['password']) < 8) { // mb_strlen - возвращает количество символов в строке
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }

        if (static::findOneByColumn('nickname', $userData['nickname']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким nickname уже существует');
        }

        if (static::findOneByColumn('email', $userData['email']) !== null) {
            throw new InvalidArgumentException('Пользователь с таким email уже существует');
        }

        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->isConfirmed = false; // подтверждён ли зарегистрированный пользователь, по умолчанию нет
        $user->role = 'user';
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100)); // random_bytes - генерирует криптографически безопасные псевдослучайные байты. sha1 - вычисляет хэш строки
        $user->save();

        return $user;

    }

    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }

    public static function login(array $loginData): User
    {
        if (empty($loginData['email'])) {
            throw new InvalidArgumentException('Не передан email');
        }

        if (empty($loginData['password'])) {
            throw new InvalidArgumentException('Не передан password');
        }

        $user = User::findOneByColumn('email', $loginData['email']);
        if ($user === null) {
            throw new InvalidArgumentException('Нет пользователя с таким email');
        }

        if (!password_verify($loginData['password'], $user->getPasswordHash())) {
            throw new InvalidArgumentException('Неправильный пароль');
        }

        if (!$user->isConfirmed) {
            throw new InvalidArgumentException('Пользователь не подтвержён');
        }

        $user->refreshAuthToken();
        $user->save();

        return $user;
    }


    public function refreshAuthToken()
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }

    public function updateNickname(array $fields): User
    {
        if (empty($fields['nickname'])){
            throw new InvalidArgumentException('Не передан новый nickname');
        }

        $this->setNickname($fields['nickname']);
        $this->save();

        return $this;
    }
    public function updatePassword(array $fields): User
    {
        if ((empty($fields['password1'])) && (empty($fields['password2'])) ){
            throw new InvalidArgumentException('Поля пустые');
        }
        if (mb_strlen($fields['password1']) < 8) { // mb_strlen - возвращает количество символов в строке
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');
        }

        if ((empty($fields['password1'])) || (empty($fields['password2'])) ){
            throw new InvalidArgumentException('Один из полей пустой');
        }

        if ($fields['password1'] !== $fields['password2']){
            throw new InvalidArgumentException('Пароли не совпадают');
        }

        $passwordHash = password_hash($fields['password1'], PASSWORD_DEFAULT);
        $this->setPasswordHash($passwordHash);
        $this->save();

        return $this;
    }

    public function updateImage($fields): User
    {
        if (empty($fields['attachment'])){
            throw new InvalidArgumentException('Файл не загружен');
        }

        $this->setImage($fields['attachment']);
        $this->save();

        return $this;
    }

    public function viewImage()
    {

        $this->viewImage();
    }
}