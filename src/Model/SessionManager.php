<?php


namespace App\Model;

class SessionManager extends AbstractManager
{

    const TABLE = 'user';

    private $messageAlert;

    private $pass;

    private $test;

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    public function login($post)
    {

        if (is_array($post)) {
            $this->messageAlert = '';

            $errors = array();
            if (count($errors) == 0 && !empty($post['login']) && !empty($post['password'])) {
                extract($post);
                $this->pass = sha1($post['password']);
                // $test = '';
                if (strpos($post['login'], '@')) {
                    $this->test = 'email';
                } else {
                    $this->test = 'nickname';
                }
                $sql = "SELECT id FROM " . $this->table . " WHERE " . trim($this->test) . "='" . $post['login'] . "' 
                AND password='" . $this->pass . "'";
                return $this->pdo->query($sql)->fetchAll();
            }
        }
    }

    public function logout(): bool
    {
        $_SESSION = array();
        session_destroy();
        return true;
    }

    public function signup($tableau): array
    {
        // test des mots de passes
        // test du CP et de la ville
        // test de la date de naissance
        // test de la taille de l'image envoyée
        // Si c'est bon on envoie vers la page de configuration des skills
        $errors = [];
        //var_dump($tableau);
        if ($this->testLogin($tableau['login'])) {
            $errors['login'] = true;
        } else {
            //return false;
            echo '';
        }
        if ($this->testAdresse($tableau['zipcode'], $tableau['city'])) {
            $errors['adresse'] = true;
        }
        //var_dump($errors);
        return $errors;
    }

    private function testLogin(string $login): bool
    {
        $sql = "SELECT nickname FROM " . $this->table;

        $tableau = ($this->pdo->query($sql)->fetchAll());

        foreach ($tableau as $value) {
            if (ucfirst($login) == $value['nickname']) {
                return true;
            }
        }
        return false;
    }

    private function testAdresse(string $zipCode, string $ville): bool
    {
        if ($zipCode != "67000" || strtolower($ville) != "strasbourg") {
            return true;
        }
        return false;
    }
}
