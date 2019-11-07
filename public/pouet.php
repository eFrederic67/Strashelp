<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/debug.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Controller/SessionController.php';

use App\Model\SessionManager;

$errors = new SessionManager();
$dateOfBirth = $errors->testErrorInForm($_POST);
//var_dump($dateOfBirth);
foreach ($dateOfBirth as $key => $value) {
    echo '{% if errors.'.$key.' != "" %}' . "\n\t" .
        '<h5 class="h6 text-danger">{{ errors.' . $key . ' }}</h5>' . "\n" . '{% endif %}' . "\n";
    echo "\n";
}
