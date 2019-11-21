<?php
/**
 * Created by PhpStorm.
 * User: sylvain
 * Date: 07/03/18
 * Time: 20:52
 * PHP version 7
 */
namespace App\Model;

use App\Model\Connection;

/**
 * Abstract class handling default manager.
 */
abstract class AbstractManager
{
    /**
     * @var \PDO
     */
    protected $pdo; //variable de connexion

    /**
     * @var string
     */
    protected $table;
    /**
     * @var string
     */
    protected $className;


    /**
     * Initializes Manager Abstract class.
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->table = $table;
        $this->className = __NAMESPACE__ . '\\' . ucfirst($table);
        $this->pdo = (new Connection())->getPdoConnection();
    }

    /**
     * Get all row from database.
     *
     * @return array
     */
    public function selectAll(): array
    {
        return $this->pdo->query('SELECT * FROM ' . $this->table)->fetchAll();
    }

    /**
     * Get one row from database by ID.
     *
     * @param  int $id
     *
     * @return array
     */
    public function selectOneById(int $id)
    {
        // prepared request
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function displayCategory()
    {
        $statements = $this->pdo->prepare("SELECT * FROM category");
        $statements->execute();
        return $statements->fetchAll();
    }

    public function isAdmin()
    {
        $statements = $this->pdo->prepare("SELECT admin FROM user WHERE id=".$_SESSION['Auth']['id']);
        $statements->execute();
        return $statements->fetch();
    }

    private function testTypeMime($fichier, $type, $agretator) :bool
    {
        foreach ($type as $item) {
            if ($fichier == $agretator.$item) {
                return true;
            }
        }
        return false;
    }

    public function testImage($rep)
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
            $extension = pathinfo($_FILES['fichier']['name'].'/'.$rep, PATHINFO_EXTENSION);
            // on concatène le nom de fichier unique avec l'extension récupérée
            $filename = $rep."_".uniqid() . '.' . $extension;

            // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés
            // (attention ce dossier doit être accessible en écriture)
            $uploadDir = 'assets/images/'.$rep.'/';
            // on génère un nom de fichier à partir du nom de fichier sur le poste du client
            // (mais vous pouvez générer ce nom autrement si vous le souhaitez)
            $uploadFile = $uploadDir . $filename;

            // on déplace le fichier temporaire vers le nouvel emplacement sur le serveur.
            // Ca y est, le fichier est uploadé
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadFile)) {
                return $uploadFile;
            }
        }
    }
}
