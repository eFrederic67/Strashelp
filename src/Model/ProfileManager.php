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
class ProfileManager extends AbstractManager
{
    /**
     *
     */
    const TABLE = 'user';

    /**
     *  Initializes this class.
     */
    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    /**
     * @param array $profile
     * @return int
     */
    public function insert(array $profile): int
    {
        // prepared request
        $statement = $this->pdo->prepare("INSERT INTO $this->table (`title`) VALUES (:title)");
        $statement->bindValue('title', $profile['title'], \PDO::PARAM_STR);

        if ($statement->execute()) {
            return (int)$this->pdo->lastInsertId();
        }
    }


    /**
     * @param int $id
     */
    public function delete(int $id): void
    {
        // prepared request
        $statement = $this->pdo->prepare("DELETE FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }


    /**
     * @param array $myprofile
     * @return bool
     */

    public function update(array $myprofile):bool
    {
        if (strpos($_SESSION['Auth']['login'], '@')) {
            $test = 'email';
        } else {
            $test ='login';
        }

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table 
        SET `email` = :email, `login`=:login, `adresse_1`=:adresse_1, `adresse_2`=:adresse_2,
        `phone`=:phone, `description`=:description WHERE password=:pass AND $test=:login");
        $statement->bindValue('pass', $_SESSION['Auth']['pass'], \PDO::PARAM_INT);
        $statement->bindValue('email', $myprofile['email'], \PDO::PARAM_STR);
        $statement->bindValue('login', $myprofile['login'], \PDO::PARAM_STR);
        $statement->bindValue('adresse_1', $myprofile['adresse_1'], \PDO::PARAM_STR);
        $statement->bindValue('adresse_2', $myprofile['adresse_2'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $myprofile['phone'], \PDO::PARAM_STR);
        $statement->bindValue('description', $myprofile['description'], \PDO::PARAM_STR);
        $statement->bindValue('login', $_SESSION['Auth']['login'], \PDO::PARAM_STR);

        return $statement->execute() ;
    }

    public function session()
    {
        if (strpos($_SESSION['Auth']['login'], '@')) {
            $test = 'email';
        } else {
            $test ='login';
        }

        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE `password`=:pass AND $test=:login");
        $statement->bindValue('pass', $_SESSION['Auth']['pass'], \PDO::PARAM_STR);
        $statement->bindValue('login', $_SESSION['Auth']['login'], \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }

    public function skill(array $profile)
    {
        $skills = [];
        if ($profile['admin'] == 0) {
            $profile['membre'] = "Membre de l'association";
        } else {
            $profile['membre'] = "Administrateur";
        }
        if ($profile['éducation'] == 1) {
            $skills[] = 'Éducation';
        } if ($profile['cuisine'] == 1) {
            $skills[] = 'Cuisine';
        } if ($profile['bricolage'] == 1) {
            $skills[] = 'Bricolage';
        }
        return $skills;
    }

    public function annonces(array $profil)
    {
        $statement = $this->pdo->prepare("SELECT * FROM `post` WHERE id_user=:id");
        $statement->bindValue('id', $profil['id'], \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}
