<?php

namespace App\Model;

use App\Model\Interfaces\AddPostInterfaces;

class SearchManager extends AbstractManager implements AddPostInterfaces
{
    const TABLE = 'post';
    const TUPLES = ['title', 'type', 'id_category', 'id_keyword','start_hour', 'end_hour', 'id_user',
        'date_publication', 'text_annoucement', 'nbmin', 'nbmax'];

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
}
