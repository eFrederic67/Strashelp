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

    // Initializes this class.
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectUserById(int $id)
    {
        if (isset($_POST['Envoyer'])) {
            try {
                $signalingUserId = $_SESSION['id'];
                $signalingUserLogin = $_SESSION['login'];
                $alertMessage = $_POST['alert_message'];
                $alertDate = date("d/m/o H:i:s");
                $query = "INSERT INTO $this->table
                   VALUES (NULL, :user_id, :user_login, :signaled_user_id, :alert_message, :alert_date)";
                $statement = $this->pdo->prepare($query);

                $statement->bindValue(':user_id', $signalingUserId, PDO::PARAM_STR);
                $statement->bindValue(':user_login', $signalingUserLogin, PDO::PARAM_STR);
                $statement->bindValue(':signaled_user_id', $id, PDO::PARAM_STR);
                $statement->bindValue(':alert_message', $alertMessage, PDO::PARAM_STR);
                $statement->bindValue(':alert_date', $alertDate, PDO::PARAM_STR);

                $statement->execute();

                // Redirection vers la page du profil
                header("Location: /Profile/profile");
            } catch (\Exception $e) {
                echo('Erreur : ' . $e->getMessage());
            }
        }
    }
}
