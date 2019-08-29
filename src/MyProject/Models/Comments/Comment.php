<?php

namespace MyProject\Models\Comments;

use \MyProject\Models\Users\User;
use \MyProject\Models\ActiveRecordEntity;
use \MyProject\Exceptions\InvalidArgumentException;

class Comment extends ActiveRecordEntity
{
    /** @var string */
    protected $userId;

    /** @var string */
    protected $articleId;

    /** @var string */
    protected $text;

    /** @var string */
    protected $createdAt;


    public function getUserId():int
    {
        return $this->userId;
    }

    public function setArticleId(int $articleId)
    {
        $this->articleId = $articleId;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    protected static function getTableName(): string
    {
        return 'comments';
    }

    /**
     * @return int
     */
    public function getAuthor(): User
    {
        return User::getById($this->userId);
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $authorcomment): void
    {
        $this->userId = $authorcomment->getId();
    }

    public function getArticleId()
    {
        return $this->articleId;
    }

    public function setCreatedAt()
    {
        $this->createdAt = date("Y-m-d H:i:s");
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function updateFromArray(array $fields): Comment
    {

        if (empty($fields['text'])){
            throw new InvalidArgumentException('Не передан текст комментария');
        }
        $this->setCreatedAt();
        $this->setText($fields['text']);
        $this->save();

        return $this;
    }
    public function createFromArray(array $fields, User $authorcomment, int $articleId): Comment
    {

        if (empty($fields['text'])){
            throw new InvalidArgumentException('Не передан текст комментария');
        }
        $comment = new Comment();
        $comment->setAuthor($authorcomment);
        $comment->setText($fields['text']);
        $comment->setArticleId($articleId);

        $comment->save();

        return $comment;
    }



}
