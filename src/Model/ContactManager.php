<?php

namespace App\Model;

use PDO;

class ContactManager extends AbstractManager
{
    const TABLE = 'helpmessage';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function selectUserById(int $id)
    {
        if (isset($_POST['Envoyer'])) {
            try {
                $theme = $_POST['theme'];
                $message = $_POST['message'];
                $messageDate = date("d/m/o H:i:s");
                $query = "INSERT INTO $this->table
                   VALUES (NULL, :user_id, :theme, :message, :message_date)";
                $statement = $this->pdo->prepare($query);

                $statement->bindValue(':user_id', $id, PDO::PARAM_INT);
                $statement->bindValue(':theme', $theme, PDO::PARAM_STR);
                $statement->bindValue(':message', $message, PDO::PARAM_STR);
                $statement->bindValue(':message_date', $messageDate, PDO::PARAM_STR);

                $statement->execute();

                // Redirection vers la page de contact
                header("Location: /Contact/contact");
            } catch (\Exception $e) {
                echo('Erreur : '.$e->getMessage());
            }
        }
    }
}
