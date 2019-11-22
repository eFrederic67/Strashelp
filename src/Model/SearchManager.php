<?php

namespace App\Model;

use App\Model\Interfaces\AddPostInterfaces;
use App\Model\Interfaces\PostInterfaces;

class SearchManager extends AbstractManager implements AddPostInterfaces, PostInterfaces
{
    const TABLE = 'post';
    const TUPLES = ['title', 'type', 'id_category' ,'start_hour', 'end_hour',
        'date_publication', 'text_annoucement'];

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

    public function addPost(array $item)
    {
        $item['id_user'] = $_SESSION['Auth']['id'];
        $statement = $this->pdo->prepare("INSERT INTO ".self::TABLE."(id_user, title, type, id_category, start_hour, 
        end_hour, date_publication, text_annoucement, nbmin, nbmax) VALUES 
        (:id_user, :title, :type, :id_category,:start_hour, :end_hour, :date_publication, :text_annoucement, :nbmin,
         :nbmax)");
        $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);
        $statement->bindValue('id_user', $item['id_user'], \PDO::PARAM_INT);
        $statement->bindValue('type', $item['type'], \PDO::PARAM_INT);
        $statement->bindValue('id_category', $item['id_category'], \PDO::PARAM_STR);

        $startHour = $item['date_publication']." ".$item['start_hour'].":00";
        $endHour = $item['date_publication']." ".$item['end_hour'].":00";

        $statement->bindValue('start_hour', $startHour, \PDO::PARAM_STR);
        $statement->bindValue('end_hour', $endHour, \PDO::PARAM_STR);
        $statement->bindValue('date_publication', date("Y-m-d H:i:s"), \PDO::PARAM_STR);
        $statement->bindValue('text_annoucement', $item['text_annoucement'], \PDO::PARAM_STR);
        $statement->bindValue('nbmin', $item['nbmin'], \PDO::PARAM_INT);
        $statement->bindValue('nbmax', $item['nbmax'], \PDO::PARAM_INT);
        return ($statement->execute()? true:false);
    }

    public function post(int $id)
    {
        $statement = $this->pdo->prepare("SELECT post.id, type, title, id_user, category.name AS catname, user.login,
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

    public function modifPost(int $id)
    {
        $statement = $this->pdo->prepare(
            "UPDATE ". self::TABLE ." 
            SET title=:title, type=:type, 
            id_category=:id_category, start_hour=:start_hour,
            end_hour=:end_hour, date_publication=:date_publication, 
            text_annoucement=:text_annoucement, nbmin=:nbmin, nbmax=:nbmax WHERE id=:id"
        );

        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->bindValue('title', $_POST['title'], \PDO::PARAM_STR);
        $statement->bindValue('type', $_POST['type'], \PDO::PARAM_INT);
        $statement->bindValue('id_category', $_POST['id_category'], \PDO::PARAM_STR);
        $statement->bindValue('start_hour', $_POST['start_hour'], \PDO::PARAM_STR);
        $statement->bindValue('end_hour', $_POST['end_hour'], \PDO::PARAM_STR);
        $statement->bindValue('date_publication', $_POST['date_publication'], \PDO::PARAM_STR);
        $statement->bindValue('text_annoucement', $_POST['text_annoucement'], \PDO::PARAM_STR);
        $statement->bindValue('nbmin', $_POST['nbmin'], \PDO::PARAM_INT);
        $statement->bindValue('nbmax', $_POST['nbmax'], \PDO::PARAM_INT);
        return $statement->execute() ;
    }

    public function deleteOnePost(int $id)
    {
        $statement = $this->pdo->prepare("DELETE FROM ".self::TABLE." WHERE id = :id");
        $statement->execute(['id' => $id]);
    }
}
