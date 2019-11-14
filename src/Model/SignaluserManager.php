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

class SignaluserManager extends AbstractManager
{
    const TABLE = 'useralert';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function signalUserById(int $id)
    {
        if (!empty($_POST)) {
            $signalingUserId = $_SESSION['Auth']['id'];
            $signalingUserLogin = $_SESSION['Auth']['login'];
            $alertMessage = $_POST['alert_message'];
            $query = "INSERT INTO $this->table
                   VALUES (NULL, :user_id, :user_login, :signaled_user_id, :alert_message, CURRENT_DATE(), NULL)";
            $statement = $this->pdo->prepare($query);

            $statement->bindValue(':user_id', $signalingUserId, PDO::PARAM_STR);
            $statement->bindValue(':user_login', $signalingUserLogin, PDO::PARAM_STR);
            $statement->bindValue(':signaled_user_id', $id, PDO::PARAM_STR);
            $statement->bindValue(':alert_message', $alertMessage, PDO::PARAM_STR);

            $statement->execute();

            header("Location: /Profile/profile/" . $id);
        } else {
            $sql = "SELECT * FROM user WHERE id=" . $id;
            return $this->pdo->query($sql)->fetch();
        }
        return $statement;
    }
}
