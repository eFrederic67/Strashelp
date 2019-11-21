<?php


namespace App\Model;

class BlogManager extends AbstractManager
{
    const TABLE = 'article';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function getLastEntry($table):array
    {
        // prepared request
        $statement = $this->pdo->query("SELECT * FROM ".$table." ORDER BY id DESC LIMIT 1");

        return $statement->fetch();
    }

    public function insertInDB($post)
    {
        $sql ="INSERT INTO article(title, bodytext, id_user, date_creation, date_publication, id_category, image)
                VALUES (:title, :bodytext, :id_user, '". date("Y-m-d H:i:s") ."', :date_publication,
                :id_category, :image)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('title', $post['title'], \PDO::PARAM_STR);
        $statement->bindValue('id_category', $post['id_category'], \PDO::PARAM_STR);
        $statement->bindValue('bodytext', $post['bodytext'], \PDO::PARAM_STR);
        $statement->bindValue('id_user', $_SESSION['Auth']['id'], \PDO::PARAM_STR);
        $statement->bindValue('image', $post['image'], \PDO::PARAM_STR);
        $statement->bindValue('date_publication', $post['date_publication']. " " . $post['end_hour'], \PDO::PARAM_STR);

        return ($statement->execute()) ? true : false;
    }
}
