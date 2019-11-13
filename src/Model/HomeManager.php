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

    public function selectBySection($table, $id)
    {
        return $this->pdo->query('SELECT * FROM ' . $table . ' 
         WHERE id_user ='.$id.' AND DATE(start_hour) >= CURDATE()')->fetchAll();
    }

    public function peopleInNeed($table, $id)
    {
        $category = $this->pdo->query('SELECT id, bricolage, cuisine, éducation
        FROM user WHERE id='.$id)->fetchAll();
        $cat[1] = ($category[0]['bricolage']) ? 1 : 0;
        $cat[2] = ($category[0]['cuisine']) ? 2 : 0;
        $cat[3] = ($category[0]['éducation']) ? 3 : 0 ;
        $cat = array_diff($cat, [0]);
        $cat = implode(",",$cat);


        $sql = 'SELECT post.id, post.type as type, post.title as title, category.name as category, 
        start_hour, end_hour, user.login as user, nbmin, nbmax, category.id FROM ' . $table . '
        JOIN category ON id_category = category.id
        JOIN user on user.id = id_user 
        WHERE id_user <> '.$id.' AND id_category in ('. $cat .') AND DATE(start_hour) >= CURDATE()';
        var_dump($sql);
        return $this->pdo->query($sql)->fetchAll();
    }
}
