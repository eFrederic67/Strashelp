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

    public function selectBySection($table, $id, $type)
    {
        $tableau = $this->pdo->query('SELECT *, category.name as cat FROM '.$table.'
         JOIN category ON category.id = id_category
         WHERE id_user ='.$id.' AND post.type = '. $type .' AND DATE(start_hour) >= CURDATE()')->fetchAll();
        $tableau = $this->howManyAnswers($tableau);
        return $tableau;
    }

    public function peopleInNeed($table, $id)
    {
        $category = $this->pdo->query('SELECT id, bricolage, cuisine, éducation
        FROM user WHERE id='.$id)->fetchAll();
        $cat[1] = ($category[0]['bricolage']) ? 1 : 0;
        $cat[2] = ($category[0]['cuisine']) ? 2 : 0;
        $cat[3] = ($category[0]['éducation']) ? 3 : 0 ;
        $cat = array_diff($cat, [0]);
        $cat = implode(",", $cat);
        if (strlen($cat) > 0) {
            $sql = 'SELECT post.id as id_post, post.type as type, post.title as title, category.name as category,
                    start_hour, end_hour, user.login as user, nbmin, nbmax, category.id FROM ' . $table . '
                    JOIN category ON id_category = category.id
                    JOIN user on user.id = id_user
                    WHERE id_user <> '.$id.' AND id_category in ('. $cat .') AND DATE(start_hour) >= CURDATE()';

            $tableau = $this->pdo->query($sql)->fetchAll();
            $tableau = $this->howManyAnswers($tableau);
            return $tableau;
        }
        return [];
    }

    public function lastPosts($id)
    {
        $sql = "SELECT post.id, post.type, post.id_category, start_hour, end_hour, post.title, nbmin, nbmax,
                user.login as user, category.name as category FROM post 
                JOIN user ON user.id = id_user 
                JOIN category ON category.id = post.id_category
                WHERE id_user <>".$id." 
                ORDER  BY post.id DESC LIMIT 8";

        $tableau = $this->pdo->query($sql)->fetchAll();
        $tableau = $this->howManyAnswers($tableau);

        return $tableau;
    }

    public function howManyAnswers($tableau)
    {
        foreach ($tableau as $key => $value) {
            $sql = "SELECT count(*) FROM response WHERE id_post =".$value['id'];
            $pouet = $this->pdo->query($sql)->fetch();
            $tableau[$key]['reponse'] = $pouet['count(*)'];
        }
        return $tableau;
    }

    public function lastArticle()
    {
        $sql = "SELECT * FROM article ORDER BY date_publication DESC LIMIT 4";
        $pouet = $this->pdo->query($sql)->fetchall();
        return $pouet;
    }

    public function topHelpers()
    {
        $sql = "SELECT user.id, user.avatar, user.login FROM user JOIN friend ON user.id = friend.id_friend";
        $pouet = $this->pdo->query($sql)->fetchall(\PDO::FETCH_NUM);
        $tableau = [];
        foreach ($pouet as $key => $value) {
            $tableau[$key] = $value[0];
        }
        $tableau2 = array_count_values($tableau);

        $temp = [];
        for ($i = 0; $i < 3; $i++) {
            $temp[$i] = array_search(max($tableau2), $tableau2);
            unset($tableau2[$temp[$i]]);
        }

        $topHelpers =[];
        for ($i = 0; $i < 3; $i++) {
            $sql = "SELECT user.id, user.avatar, user.login FROM user WHERE id=" . $temp[$i];
            $topHelpers[$i] = $this->pdo->query($sql)->fetch(\PDO::FETCH_NUM);
        }

        return $topHelpers;
    }
}
