<?php

namespace App\Model;

class SearchuserManager extends AbstractManager
{
    // TODO utiliser les examples ci-dessous :

    const TABLE = 'user';
    const TUPLES = [ //'title', 'type', 'id_category' ,'start_hour', 'end_hour',
        //'date_publication', 'text_annoucement'
        ];

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function search()
    {
        $select = $this->pdo->prepare("SELECT post.id AS post_id, type, title, id_category, user.login,
        DATE_FORMAT(start_hour, '%d/%m/%Y') AS start_day, DATE_FORMAT(start_hour, '%Hh%i') AS start_hour,
        DATE_FORMAT(end_hour, '%Hh%i') AS end_hour, text_annoucement, nbmin, nbmax, user.id FROM ". self::TABLE."
        JOIN user ON user.id = post.id_user ");
        $select->execute();
        return $select->fetchAll();
    }

    public function displayUsers()
    {
        $statement = $this->pdo->prepare("SELECT * FROM ".self::TABLE);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function post(int $id)
    {
        $statement = $this->pdo->prepare("SELECT post.id, type, title, category.name AS catname, user.login,
        DATE_FORMAT(start_hour, '%d/%m/%Y') AS start_day, DATE_FORMAT(start_hour, '%Hh%i') AS start_hour, 
        DATE_FORMAT(end_hour, '%Hh%i') AS end_hour, text_annoucement, nbmin, nbmax FROM ".self::TABLE."
        JOIN user ON user.id = post.id_user JOIN category ON category.id = post.id_category WHERE post.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }

    public function getLastEntry()
    {
        $statement = $this->pdo->query("SELECT * FROM ".self::TABLE." ORDER BY id DESC LIMIT 1");
        return $statement->fetch();
    }
}
