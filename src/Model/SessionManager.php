<?php


namespace App\Model;

use DateInterval;
use DateTime;

class SessionManager extends AbstractManager
{

    const TABLE = 'user';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function login($post)
    {
        $login="";
        $password="";
        if (is_array($post)) {
            $errors = array();
            if (count($errors)==0 && !empty($post['login']) && !empty($post['password'])) {
                extract($post);
                $pass = sha1($password);

                if (strpos($login, '@')) {
                    $test = 'email';
                } else {
                    $test ='login';
                }

                $sql = "SELECT id FROM ".$this->table ." WHERE ".trim($test)."='".$login."' AND password='".$pass."'";
                return $this->pdo->query($sql)->fetchAll();
            }
        }
    }

    public function logout():bool
    {
        $_SESSION = array();
        session_destroy();
        return true;
    }

    public function testErrorInForm($post)
    {
        $errors = array();
        if ($this->testDoublon('login', $post['login'])) {
            $errors['login'] = "Le login que vous avez choisi est déjà utilisé";
        }

        if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
        } else {
            $errors['email'] = $post['email'] . " n'est pas une adresse valide !";
        }

        if ($post['emailConf']!= $post['email']) {
            $errors['login'] = "les deux adresses mails entrées sont différentes";
        }

        if (checkdate($post['monthOfBirth'], $post['dayOfBirth'], $post['yearOfBirth'])) {
            $bidule = $post['yearOfBirth']."/".$post['monthOfBirth']."/".$post['dayOfBirth'];
            $dateNaissance = DateTime::createFromFormat('Y/m/d', $bidule);
            $date = new DateTime(date('m/d/Y h:i:s a', time()));
            $date18 = $date->sub(new DateInterval('P18Y'));
            if ($dateNaissance > $date18) {
                $errors['dateOfBirth'] = "Désolé, vous devez être majeur(e) pour pouvoir vous inscrire";
            }
        }

        return $errors;
    }

    public function requete($post)
    {
        //donc là, il faut préparer une requête pour insérer les champs dans la base de données

        // On récupère les champs de la table self::TABLE
        $query = 'DESCRIBE '.self::TABLE;
        $statement = $this->pdo->prepare($query);
        $statement->execute();
        $champs = $statement->fetchAll();

        $champsAInserer=[];
        $requete ="INSERT INTO ".self::TABLE."(";

        $lenTemp = count($champs);
        for ($i = 1; $i<$lenTemp; $i++) {
            array_push($champsAInserer, $champs[$i]['Field']);
            $requete .= $champs[$i]['Field'].", ";
        }

        $requete = substr($requete, 0, strlen($requete)-2);
        $requete .= ") VALUES (";

        foreach ($champsAInserer as $value) {
            if (null !== ($post[$value])) {
                $requete .= ":".$value.", ";
            }
        }
        $requete = substr($requete, 0, strlen($requete)-2);
        $requete .= ")";
        $insertion = $this->pdo->prepare($requete);
        foreach ($champsAInserer as $value) {
            $insertion->bindValue($value, $post[$value], \PDO::PARAM_STR);
        }
        // ajouter un test pour être sûr que ça s'est bien passé et un return
        $insertion->execute();
    }

    public function testDoublon($field, $valeur)
    {
        $requete = "SELECT " . $field . " FROM " . self::TABLE . " WHERE " . $field . "='" . $valeur."'";
        $insertion = $this->pdo->prepare($requete);
        //si la requête fonctionne et qu'il y a une entrée existante, on renvoie vrai
        if ($insertion->execute()) {
            return (count($insertion->fetchAll())>0) ? true : false;
        }
    }
}
