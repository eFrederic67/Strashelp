<?php


namespace App\Model;

use DateInterval;
use DateTime;

class HomeManager extends AbstractManager
{

    const TABLE = 'user';


    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectBySection($table,$id)
    {
        return $this->pdo->query('SELECT * FROM ' . $table . ' WHERE id_user='.$id)->fetchAll();
    }
}
