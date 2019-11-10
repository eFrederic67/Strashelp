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
        // Si le type EST loggué, on l'envoie sur la page d'accueil des loggués
        if (isset($_SESSION['Auth']) && isset($_SESSION['Auth']['login']) && isset($_SESSION['Auth']['pass'])) {
            return $this->twig->render('Home/homeLogged.html.twig', ['firstname' => $_SESSION['Auth']['firstname']]);
        } else {
            return $this->twig->render('Home/index.html.twig');
        }
    }
}
