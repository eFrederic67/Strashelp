<?php


namespace App\Model;

class SessionManager extends AbstractManager
{

    const TABLE = 'user';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }
/*
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table)->fetchAll();
    }
*/
    public function login($post)
    {

        if (is_array($post)) {
            $message = '';
            $errors = array();
            if (count($errors)==0 && isset($post) && !empty($post['login']) && !empty($post['password'])) {
                extract($post);
                $pass = sha1($password);
                $test = '';
                if (strpos($login, '@')) {
                    $test = 'email';
                } else {
                    $test ='nickname';
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

    public function signup($tableau):array
    {
        // test des mots de passes
        // test du CP et de la ville
        // test de la date de naissance
        // test de la taille de l'image envoyée
        // Si c'est bon on envoie vers la page de configuration des skills
        $errors= [];
        //var_dump($tableau);
        if ($this->testLogin($tableau['login'])) {
            $errors['login'] = true;
        } else {
            //return false;
        }
        if ($this->testAdresse($tableau['zipcode'], $tableau['city'])) {
            $errors['adresse'] = true;
        }
        //var_dump($errors);
        return $errors;
    }

    private function testLogin(string $login):bool
    {
        $sql = "SELECT nickname FROM ".$this->table;

            $tableau = ($this->pdo->query($sql)->fetchAll());

        foreach ($tableau as $key => $value) {
            if (ucfirst($login) == $value['nickname']) {
                return true;
            }
        }
        return false;
    }

    private function testAdresse(string $CP, string $ville):bool
    {
        if ($CP != "67000" || strtolower($ville) != "strasbourg") {
            return true;
        }
        return false;
    }
}
