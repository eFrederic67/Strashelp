<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\HomeManager;
use DateInterval;
use DateTime;


class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $homeManager = new HomeManager();
        // Si le type EST loggué, on l'envoie sur la page d'accueil des loggués
        if (isset($_SESSION['Auth']) && isset($_SESSION['Auth']['login']) && isset($_SESSION['Auth']['pass'])) {
            return $this->twig->render('Home/homeLogged.html.twig');
        } else {
            return $this->twig->render('Home/index.html.twig');

/*        // si le post est vide et que le visiteur n'est pas loggué,
        // affichage de l'index publique avec possibilité d'inscription (formulaire caché par défaut)
            if (empty($_POST)) {
                // si le post est vide parce que c'est le 1er loading de la page
                return $this->twig->render('Home/signup.html.twig', ['display' => 'none']);
            } else {
                // si le post est rempli parce que c'est un retour de formulaire
                // on teste les erreurs
                $errors = $homeManager->testErrorInForm($_POST);

                if (count($errors) == 0) {
                // s'il le teste d'erreur est ok

                    echo "ça marche";
                    // lancer les procédures pour ajouter la personne dans la base de donnée
                    $_POST['birthday'] = $_POST['yearOfBirth']."-".$_POST['monthOfBirth']."-".$_POST['dayOfBirth'];
                    $_POST['admin']=0;
                    $_POST['password'] = sha1($_POST['password']);
                    $maRequete = $homeManager->requete($_POST);
                    //header("Location: ../success.php/");
                    //exit;
                } else {
                // header location la suite
                    // sinon on reloade la page mais en mettant les informations.
                    return $this->twig->render('Home/signup.html.twig', [
                        'errors' => $errors,
                        'display' => 'block',
                        'login' => $_POST['login'],
                        'email' => $_POST['email'],
                        'emailConf' => $_POST['emailConf'],
                        'password' => $_POST['password'],
                        'passwordConf' => $_POST['passwordConf'],

                        'lastname' => $_POST['lastname'],
                        'firstname' => $_POST['firstname'],
                        'adresse_1' => $_POST['adresse_1'],
                        'adresse_2' => $_POST['adresse_2'],
                        'zipcode' => $_POST['zipcode'],
                        'city' => $_POST['city'],
                        'phone' => $_POST['phone'],
                        'dayOfBirth' => $_POST['dayOfBirth'],
                        'monthOfBirth' => $_POST['monthOfBirth'],
                        'yearOfBirth' => $_POST['yearOfBirth'],
                        'avatar' => $_POST['avatar'],
                        'errors' => $errors,
                    ]);
                }
            }
       */ }
    }



}
