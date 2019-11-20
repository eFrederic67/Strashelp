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
}
