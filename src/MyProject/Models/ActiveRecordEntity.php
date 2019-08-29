<?php

namespace MyProject\Models;

use MyProject\Exceptions\NotDeleted;
use MyProject\Services\Db;
use MyProject\Models\Comments\Comment;
use MyProject\Models\Users\User;

abstract class ActiveRecordEntity implements \JsonSerializable
{

    /** @var int */
    protected $id;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    function __set($name, $value)
    { // назначение значений защищённым переменным
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));//Функция ucwords() делает первые буквы в словах большими, первым аргументом она принимает строку со словами, вторым аргументом – символ-разделитель (то, что стоит между словами). После этого строка string_with_smth преобразуется к виду String_With_Smth
    }

    /**
     * @return static[]
     */
    public static function findAll(): array // извлекаем все данные из БД
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    public static function latestData(): array
    {
        $db = Db::getInstance();
        $data = $db->query('SELECT * FROM `'. static::getTableName() . '` ORDER BY `id` DESC LIMIT 10', [], static::class);
        return $data ;
    }

    abstract protected static function getTableName(): string;

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self // извлекаем id статьи
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }

    public function save(): void //сохранение изменений в БД
    {
        $mappedProperties = $this->mapPropertiesDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    private function update(array $mappedProperties): void // Обновление данных в БД
    {
        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperties as $column => $value) {
            $param = ':param' . $index; //:param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[':param' . $index] = $value; //:param1 => value1
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }

    private function insert(array $mappedProperties): void // вставка новой статьив в БД
    {
        $filteredProperties = array_filter($mappedProperties);// фильтрует элементы массива с помощью callback-функции
                                                              // Если callback-функция не передана, все значения массива array равные FALSE будут удалены.
        $columns = [];
        $paramsNames = [];
        $params2values = [];
        foreach ($filteredProperties as $columnName => $value) {
            $columns[] = '`' . $columnName . '`'; // `column`
            $paramName = ':' . $columnName;       // :param
            $paramsNames[] = $paramName;          // массив :param
            $params2values[$paramName] = $value;  // массив :param => value
        }
        $columnsViaSemicolon = implode(', ', $columns);
        $paramsNamesViaSemicolon = implode(', ', $paramsNames);

        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . $columnsViaSemicolon . ') ' . 'VALUE (' . $paramsNamesViaSemicolon . ');';

        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }

    private function refresh(): void            // извлекаем дату из БД
    {
        $objFromDb = static::getById($this->id);
        $properties = get_object_vars($objFromDb);
        foreach ($properties as $key=>$value)
        {
            $this->$key = $value;
        }
    }

    public function deleteArticles(): void // удаление статьи
    {

            $db = Db::getInstance();
            $db->query(
                'DELETE FROM `' . static::getTableName() . '` WHERE id=:id; DELETE FROM `' . Comment::getTableName() . '` WHERE article_id=:id;', // два запроса: 1. Для удаления статьи
                    [':id' => $this->id]                                                                                                          // 2. Для удаления всех его комментариев
                );
                    $this->id = null;
    }

    public function delComment():void // удаление комментария
    {
        $db = Db::getInstance();

        $article = $db->query('SELECT `article_id` FROM `' . static::getTableName() . '` WHERE id=:id;', // запрос на извелечение id статьи
            [':id' => $this->id]                                                                         // чтобы при удалении комментария нас перенапраляло
        );                                                                                               // на страницу со статьёй
        $articleId = $article[0]->article_id; // так как создался объект, обращаемся к его элементу и извлекаем id
        unset($article); //Удаляем объект, чтобы не заполнял память

        $db->query(
            'DELETE FROM `' . Comment::getTableName() . '` WHERE id=:id;',
            [':id' => $this->id]
        );
        $this->id = null;
        header('Location:/articles/'. $articleId, 302);
    }

    public function delUser(): void //удаление аккаунта с его комментариями
    {

        $db = Db::getInstance();

        $db->query(
            'DELETE FROM `' . User::getTableName() . '` WHERE id=:id; DELETE FROM `' . Comment::getTableName() . '` WHERE user_id=:id;',
            [':id' => $this->id]
        );
        $this->id = null;
        header('Location:/', 302);
    }


    private function mapPropertiesDbFormat(): array // функция преобразования названия свойства объекта в название атрибута в БД для манипуляции с данными в БД
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }
        return $mappedProperties;
    }

    private function camelCaseToUnderscore(string $source): string      // преобразование строки типа authorId в author_id
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source)); //strtolower — Преобразует строку в нижний регистр
    }                                                                                        //preg_replace — Выполняет поиск и замену по регулярному выражению

    public static function findOneByColumn(string $columnName, $value): ?self // поиск дубликатов в базе при регистрации нового пользователя
    {
        $db = Db::getInstance();
        $result = $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
            [':value' => $value],
            static::class);
        if ($result === []){
            return null;
        }
        return $result[0];
    }

    public function jsonSerialize()
    {
        return $this->mapPropertiesDbFormat();
    }

}