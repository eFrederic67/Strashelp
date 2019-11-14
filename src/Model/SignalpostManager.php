<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

use PDO;

class SignalpostManager extends AbstractManager
{
    const TABLE = 'postalert';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function signalPostById(int $id)
    {
        if (!empty($_POST)) {
                $signalingUserId = $_SESSION['id'];
                $signalingUserLogin = $_SESSION['login'];
                $alertMessage = $_POST['alert_message'];
                $query = "INSERT INTO $this->table
                   VALUES (NULL, :user_id, :user_login, :post_id, :alert_message, CURRENT_DATE(), NULL)";
                $statement = $this->pdo->prepare($query);

                // $statement->bindValue(':post_title', $postTitle, PDO::PARAM_STR);
                $statement->bindValue(':user_id', $signalingUserId, PDO::PARAM_STR);
                $statement->bindValue(':user_login', $signalingUserLogin, PDO::PARAM_STR);
                $statement->bindValue(':post_id', $id, PDO::PARAM_STR);
                $statement->bindValue(':alert_message', $alertMessage, PDO::PARAM_STR);

                $statement->execute();

                header("Location: /Search/post" . $id);
        }
    }

    public function selectPostTitleById(int $id)
    {
        $query = "SELECT title FROM post WHERE id=:id";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue('id', $id, \PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetch();
    }
}
