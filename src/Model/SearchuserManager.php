<?php

namespace App\Model;

class SearchuserManager extends AbstractManager
{
    const TABLE = 'user';
    /* const TUPLES = ['lastname', 'firstname', 'login' ,'adresse_1', 'adresse_2',
        'zipcode', 'city', 'phone', 'email', 'birthday', 'admin', 'avatar', 'password', 'description', 'bricolage',
        'cuisine', 'éducation'
        ];
    */

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function search($id)
    {
        $select = $this->pdo->prepare("SELECT id, lastname, firstname, login, admin, avatar, 
        description, bricolage, cuisine, éducation AS education FROM ".self::TABLE."WHERE id=:id");
        $select->bindValue('id', $id, \PDO::PARAM_INT);
        $select->execute();
        return $select->fetchAll();
    }

    public function displayUsers()
    {
        $display = $this->pdo->prepare("SELECT id, lastname, firstname, login, admin, avatar, 
        description, bricolage, cuisine, éducation AS education FROM ".self::TABLE);
        $display->execute();
        return $display->fetchAll();
    }
    public function modifUser(int $id)
    {
           $sql = "UPDATE ". self::TABLE ." 
            SET email = :email, login=:login, adresse_1=:adresse_1, adresse_2=:adresse_2,
            phone=:phone, description=:description";
        if (isset($_POST['avatar'])) {
            $sql .= " avatar=:avatar";
        }
        $sql .= " WHERE id=:id";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->bindValue('email', $_POST['email'], \PDO::PARAM_STR);
        $statement->bindValue('login', $_POST['login'], \PDO::PARAM_STR);
        $statement->bindValue('adresse_1', $_POST['adresse_1'], \PDO::PARAM_STR);
        $statement->bindValue('adresse_2', $_POST['adresse_2'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $_POST['phone'], \PDO::PARAM_STR);
        $statement->bindValue('description', $_POST['description'], \PDO::PARAM_STR);
        if (isset($_POST['avatar'])) {
            $statement->bindValue('avatar', $_POST['avatar'], \PDO::PARAM_STR);
        }

        return $statement->execute();
    }

    public function deleteOneUser(int $id)
    {
        $statement = $this->pdo->prepare("DELETE FROM ".self::TABLE." WHERE id = :id");
        $statement->execute(['id' => $id]);
    }
}
