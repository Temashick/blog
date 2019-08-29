<?php

namespace MyProject\Models\Articles;

use MyProject\Exceptions\NotFoundException;
use MyProject\Models\ActiveRecordEntity;
use MyProject\Models\Users\User;
use MyProject\Exceptions\InvalidArgumentException;
use MyProject\Services\Db;

class Article extends ActiveRecordEntity
{

    /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var string */
    protected $authorId;

    /** @var string */
    protected $createdAt;

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setText(string $text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getParsedText(): string
    {
        $parser = new \Parsedown();
        return $parser->text($this->getText());
    }


    protected static function getTableName(): string
    {
        return 'articles';
    }

    /**
     * @return int
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    /**
     * @param User $author
     */
    public function setAuthor(User $author): void
    {
        $this->authorId = $author->getId();
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt()
    {
        $this->createdAt = date("Y-m-d H:i:s");
    }

    public static function createFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])){
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])){
            throw new InvalidArgumentException('Не передан текст статьи');
        }

        $article = new Article();

        $article->setAuthor($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        $article->save();

        return $article;
    }

    public function updateFromArray(array $fields, User $author): Article
    {
        if (empty($fields['name'])){
            throw new InvalidArgumentException('Не передано название статьи');
        }

        if (empty($fields['text'])){
            throw new InvalidArgumentException('Не передан текст статьи');
        }
        $this->setCreatedAt();
        $this->setAuthor($author);
        $this->setName($fields['name']);
        $this->setText($fields['text']);

        $this->save();

        return $this;
    }

    public function getShortText(): string
    {
        if(empty($this->getText())){
        throw new NotFoundException();
    }else {
            return $shortText = mb_strimwidth($this->getText(), 0, 100, '...');
        }
    }
}