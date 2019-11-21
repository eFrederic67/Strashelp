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

    public function getLogin($post)
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

                $sql = "SELECT id,firstname,admin FROM ".$this->table ." WHERE ".trim($test)."='"
                    .$login."' AND password='".$pass."'";
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
        if ($post['passwordConf'] != $post['password']) {
            $errors['password'] = "Les mots de passes entrés ne sont pas identiques";
        }
        if (filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            if ($this->testDoublon('email', $post['email'])) {
                $errors['email'] = "Cette adresse mail est est déjà utilisée";
            }
        } else {
            $errors['email'] = $post['email'] . " n'est pas une adresse valide !";
        }

        if ($post['emailConf']!= $post['email']) {
            $errors['email'] = "les deux adresses mails entrées sont différentes";
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

    public function insertInDB($post)
    {
        //donc là, il faut préparer une requête pour insérer les champs dans la base de données
        // On récupère les champs de la table self::TABLE
        $post['zipcode'] = (int)$post['zipcode'];
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
                $requete .= "'".$post[$value]."', ";
            }
        }
        $requete = substr($requete, 0, strlen($requete)-2);
        $requete .= ")";
        $insertion = $this->pdo->prepare($requete);
        foreach ($champsAInserer as $value) {
            $insertion->bindValue($value, $post[$value], \PDO::PARAM_STR);
        }
        // un test pour être sûr que ça s'est bien passé et un return
        return ($insertion->execute()) ? true : false;
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

/*    private function testTypeMime($fichier, $type, $agretator) :bool
    {
        foreach ($type as $item) {
            if ($fichier == $agretator.$item) {
                return true;
            }
        }
        return false;
    }
*/
/*    public function testImage()
    {

        $typeMimeAutorises = ['png','gif','jpeg','jpg'];

        $maxSize = 1024*1024;

        $erreurSize = "";
        $erreurType = "";

        if ($_FILES['fichier']['size'] > $maxSize) {
            $erreurSize = $_FILES['fichier']['name'];
        }

        if (!$this->testTypeMime($_FILES['fichier']['type'], $typeMimeAutorises, "image/")) {
            $erreurType = $_FILES['fichier']['name'];
        }

        if ($erreurSize == '' && $erreurType == '') {
            // on récupère l'extension, par exemple "pdf"
            $extension = pathinfo($_FILES['fichier']['name'], PATHINFO_EXTENSION);
            // on concatène le nom de fichier unique avec l'extension récupérée
            $filename = "avatar_".uniqid() . '.' . $extension;

            // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés
            // (attention ce dossier doit être accessible en écriture)
            $uploadDir = 'assets/images/avatars/';
            // on génère un nom de fichier à partir du nom de fichier sur le poste du client
            // (mais vous pouvez générer ce nom autrement si vous le souhaitez)
            $uploadFile = $uploadDir . $filename;

            // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur.
            // Ca y est, le fichier est uploadé
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadFile)) {
                return $uploadFile;
            }
        }
    }*/

    public function cleanPhotosTemp():bool
    {
        // Scan de toutes les photos dans le dossier avatars
        $dir    = 'assets/images/avatars/';
        $existingFiles = scandir($dir);
        // requete de tous les noms d'avatars dans la base
        $requeteClean = "SELECT avatar FROM ".self::TABLE;
        $liste = $this->pdo->query($requeteClean)->fetchall();

        $existingFiles2=[];
        $pouet = count($existingFiles);
        for ($i = 0; $i < $pouet; $i++) {
            $existingFiles2[$i] = $dir.$existingFiles[$i];
            $existingFiles[$i] = "/".$dir.$existingFiles[$i];
        }

        $tableauAvatar = [];
        foreach ($liste as $avatar) {
            array_push($tableauAvatar, $avatar['avatar']);
        }

        $nombreDeFichiers = count($existingFiles);
        for ($i = 0; $i < $nombreDeFichiers; $i++) {
            if (!in_array($existingFiles[$i], $tableauAvatar)) {
                if ($existingFiles2[$i] == $dir."." || $existingFiles2[$i] == $dir."..") {
                } else {
                    unlink($existingFiles2[$i]);
                }
            }
        }
        return true;
    }

    public function getLastUser():array
    {
        // prepared request
        $statement = $this->pdo->query("SELECT * FROM ".self::TABLE." ORDER BY id DESC LIMIT 1");

        return $statement->fetch();
    }
}
