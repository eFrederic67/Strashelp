<?php

namespace App\Model;

class ParticipateManager extends AbstractManager
{
    const TABLE = 'response';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function addParticipation($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === $_POST['Je participe']) {
            $statement = $this->pdo->prepare("INSERT INTO ".self::TABLE." VALUES (NULL, :id_user, :id_post)");
            $item['id_user'] = $_SESSION['Auth']['id'];

            $statement->bindValue(':id_user', $item['id_user'], \PDO::PARAM_INT);
            $statement->bindValue(':id_post', $id, \PDO::PARAM_STR);

            $statement->execute();
            header('Location: /search/posts/'.$id);
        }
    }

    public function selectAuthorByPostId(int $id)
    {
        $statement = $this->pdo->prepare("SELECT id_user FROM post WHERE id=".$id);
        $statement->execute();
        return $statement->fetch();
    }

    public function getNbMax($id)
    {
        $statement = $this->pdo->prepare("SELECT nbmax FROM post WHERE id=".$id);
        return $statement->execute();
    }

    public function checkParticipation($id)
    {
        // compter si on a déjà cliqué une participation dans cette annonce
        $statement = $this->pdo->prepare("SELECT COUNT(*) FROM ".self::TABLE." 
        WHERE id_post=$id AND id_user=".$_SESSION['Auth']['id']);

        return $statement->execute();
    }

    public function deleteParticipation(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === $_POST['Annuler ma participation']) {
            $statement = $this->pdo->prepare("DELETE FROM ".self::TABLE." WHERE id_post = ".$id." 
            AND id_user = ".$_SESSION['Auth']['id']);

            $statement->execute();
            header('Location: /search/posts/'.$id);
        }
    }
}
