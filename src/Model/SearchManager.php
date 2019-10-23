<?php

namespace App\Model;

class SearchManager extends AbstractManager
{
    const TABLE = 'post';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function search(string $name)
    {
        $select = $this->pdo->prepare("SELECT * FROM ".self::TABLE." WHERE id=:post");
        $select->bindValue('post', $name, \PDO::PARAM_STR);
        $select->execute();
        return $select->fetchAll();
    }

    public function searchDate()
    {
        $select = $this->pdo->prepare("SELECT * FROM ".self::TABLE." WHERE start_hour BETWEEN :targ1 AND :targ2");
        $select->bindValue('targ1', '2019-07-23 14:00:00', \PDO::PARAM_STR);
        $select->bindValue('targ2', '2019-11-18 20:00:00', \PDO::PARAM_STR);
        $select->execute();
        return $select->fetchAll();
    }
}
