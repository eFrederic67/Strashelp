<?php

namespace App\Model;

class SearchManager extends AbstractManager
{
    const TABLE = 'post';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function search()
    {
        $select = $this->pdo->prepare("SELECT id, type, title, id_category, 
        DATE_FORMAT(start_hour, '%a %c %b %H %i') 
        AS start_hour, DATE_FORMAT(end_hour, '%a %c %b %H %i') AS end_hour, id_user,
        DATE_FORMAT(date_publication, '%a %c %b  %H %i') AS date_publication,text_annoucement, nbmin, nbmax FROM ".
        self::TABLE);
        // $select->bindValue('post', $name, \PDO::PARAM_STR);
        $select->execute();
        return $select->fetchAll();
    }

    public function addPost($item):string
    {
        $statement = $this->pdo->prepare("INSERT INTO ".self::TABLE." VALUES (:title)");
        $statement->bindValue('add', $item['add'], \PDO::PARAM_STR);
        $statement->execute();
    }
}
