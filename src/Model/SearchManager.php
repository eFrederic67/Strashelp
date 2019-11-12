<?php

namespace App\Model;

use App\Model\Interfaces\AddPostInterfaces;
use App\Model\Interfaces\PostInterfaces;

class SearchManager extends AbstractManager implements AddPostInterfaces, PostInterfaces
{
    const TABLE = 'post';
    const TUPLES = ['title', 'type', 'id_category', 'id_keyword','start_hour', 'end_hour',
        'date_publication', 'text_annoucement'];

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function search()
    {
        $select = $this->pdo->prepare("SELECT post.id, type, title, id_category, user.login,
        DATE_FORMAT(start_hour, '%d/%m/%Y') AS start_day, DATE_FORMAT(start_hour, '%Hh%i') AS start_hour,
        DATE_FORMAT(end_hour, '%Hh%i') AS end_hour, text_annoucement, nbmin, nbmax FROM ". self::TABLE."
        JOIN user ON user.id = post.id_user ");
        $select->execute();
        return $select->fetchAll();
    }

    public function addPost(array $item)
    {
        /*
         Fonction qui permet d'ajouter une annonce qui prends une constante des champs de la table post
         et ceux grace a un foreach !
         */
        $error = 0;
        $placeholder = "";
        foreach (self::TUPLES as $value) {
            if (empty($item[$value])) {
                $error++;
            }
            $placeholder.=":".$value.", ";
        }
            $placeholder = substr($placeholder, 0, strlen($placeholder)-2);
            $bidule = implode(',', self::TUPLES);
            $statement = $this->pdo->prepare("INSERT INTO ".self::TABLE."(".$bidule.")
                VALUES ($placeholder)");
        foreach (self::TUPLES as $value) {
            $statement->bindValue($value, $item[$value], \PDO::PARAM_STR);
        }
            $statement->execute();
            var_dump($statement);
    }

    public function displayCategory()
    {
        $statements = $this->pdo->prepare("SELECT category.name AS cname FROM category");
        $statements->execute();
        return $statements->fetchAll();
    }

    public function post(int $id)
    {
        $statement = $this->pdo->prepare("SELECT post.id, type, title, id_category, user.login,
        DATE_FORMAT(start_hour, '%d/%m/%Y') AS start_day, DATE_FORMAT(start_hour, '%Hh%i') AS start_hour,
        DATE_FORMAT(end_hour, '%Hh%i') AS end_hour, text_annoucement, nbmin, nbmax FROM ". self::TABLE."
        JOIN user ON user.id = post.id_user WHERE post.id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch();
    }
}
