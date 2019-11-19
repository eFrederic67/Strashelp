<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 18:20
 * PHP version 7
 */

namespace App\Model;

use App\Model\SessionManager;

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
     * @param array $post
     * @return bool
     */

    public function update(array $post):bool
    {
        if (strpos($_SESSION['Auth']['login'], '@')) {
            $test = 'email';
        } else {
            $test ='login';
        }

        // prepared request
        $statement = $this->pdo->prepare("UPDATE $this->table 
        SET `email` = :email, `login`=:pseudo, `adresse_1`=:adresse_1, `adresse_2`=:adresse_2,
        `phone`=:phone, `description`=:description WHERE password=:pass AND $test=:login");
        $statement->bindValue('pass', $_SESSION['Auth']['pass'], \PDO::PARAM_STR);
        $statement->bindValue('email', $post['email'], \PDO::PARAM_STR);
        $statement->bindValue('pseudo', $post['login'], \PDO::PARAM_STR);
        $statement->bindValue('adresse_1', $post['adresse_1'], \PDO::PARAM_STR);
        $statement->bindValue('adresse_2', $post['adresse_2'], \PDO::PARAM_STR);
        $statement->bindValue('phone', $post['phone'], \PDO::PARAM_STR);
        $statement->bindValue('description', $post['description'], \PDO::PARAM_STR);
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
            $skills['membre'] = "Membre de l'association";
        } else {
            $skills['membre'] = "Administrateur";
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


    public function testErrorInForm(array $post, array $session)
    {
        $errors = array();
        $sessionManager = new SessionManager();
        if ($post['login'] != $session['login']) {
            if ($sessionManager->testDoublon('login', $post['login'])) {
                $errors['login'] = "Le login que vous avez choisi est déjà utilisé";
            }
        }
        if ($post['email'] != $session['email']) {
            if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                if ($sessionManager->testDoublon('email', $post['email'])) {
                    $errors['email'] = "Cette adresse mail est est déjà utilisée";
                }
            } else {
                $errors['email'] = $post['email'] . " n'est pas une adresse valide !";
            }

            if ($post['emailConf'] != $post['email']) {
                $errors['email'] = "les deux adresses mails entrées sont différentes";
            }
        }
        return $errors;
    }
}
