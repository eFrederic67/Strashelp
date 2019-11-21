<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

/**
 *
 */
class FriendsManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'friend';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function delete()
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table 
        WHERE id_utilisateur=:utilisateur AND id_friend=:friend");
        $statement->bindValue('utilisateur', $_SESSION['Auth']['id'], \PDO::PARAM_INT);
        $statement->bindValue('friend', $_POST['supprimer'], \PDO::PARAM_INT);

        $statement->execute();
    }

    public function selectOneByIdUser(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table 
        WHERE id_friend=:id AND id_utilisateur=".$_SESSION['Auth']['id']);
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function add()
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (id_utilisateur, id_friend) 
        VALUES (:utilisateur, :friend)");
        $statement->bindValue('utilisateur', $_SESSION['Auth']['id'], \PDO::PARAM_INT);
        $statement->bindValue('friend', $_POST['suivre'], \PDO::PARAM_INT);

        $statement->execute();
    }
}
