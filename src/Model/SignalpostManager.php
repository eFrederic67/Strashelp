<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

use Exception;
use PDO;

class SignalpostManager extends AbstractManager
{
    const TABLE = 'item';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function signalPost(int $id)
    {
        if (isset($_POST['Envoyer'])) {
            try {
                $signalingUserId = trim($_POST['user_id']);
                $signalingUserLogin = trim($_POST['user_login']);
                $postAuthorId = trim($_POST['post_author_id']);
                $postAuthorLogin = trim($_POST['post_author_login']);
                $alertMessage = trim($_POST['alert_message']);
                $alertDate = trim($_POST['alert_date']);
                $query = "INSERT INTO alert VALUES (NULL, :user_id, :user_login, :post_id, :post_author_id, :spost_author_login, :alert_message)";
                $statement = $this->pdo->prepare($query);

                $statement->bindValue(':user_id', $signalingUserId, PDO::PARAM_STR);
                $statement->bindValue(':user_login', $signalingUserLogin, PDO::PARAM_STR);
                $statement->bindValue(':post_id', $id, PDO::PARAM_STR);
                $statement->bindValue(':post_author_id', $postAuthorId, PDO::PARAM_STR);
                $statement->bindValue(':post_author_login', $postAuthorLogin, PDO::PARAM_STR);
                $statement->bindValue(':alert_message', $alertMessage, PDO::PARAM_STR);
                $statement->bindValue(':alert_date', $alertDate, PDO::PARAM_STR);

                $statement->execute();

                // Redirection vers la page du profil
                header("Location: post.php");
            } catch (Exception $e) {
                die('Erreur : '.$e->getMessage());
            }
        }
    }

    /**
     * @param array $item
     * @return int
     *
     * public function insert(array $item): int
    {
    // prepared request
    $statement = $this->pdo->prepare("INSERT INTO $this->table (`title`) VALUES (:title)");
    $statement->bindValue('title', $item['title'], \PDO::PARAM_STR);

    if ($statement->execute()) {
    return (int)$this->pdo->lastInsertId();
    }
    }
     */

    /**
     * @param int $id
     */
}
