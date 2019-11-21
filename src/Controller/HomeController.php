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
            $homeManager = new HomeManager();
            $myAppointments = $homeManager->selectBySection('post', $_SESSION['Auth']['id']);
            $peopleInNeed = $homeManager->peopleInNeed('post', $_SESSION['Auth']['id']);
            $alignement = (count($peopleInNeed)>    3) ? "justify-content-start" : "justify-content-around";
            $lastPost = $homeManager->lastPosts($_SESSION['Auth']['id']);
            $alignementLast = (count($lastPost)>    2) ? "justify-content-start" : "justify-content-around";

            $lastArticle = $homeManager->lastArticle();
            $lastArticle = $this->trunc($lastArticle); // réduit la taille de l'article à une preview
            $alignementBlog = (count($lastPost)>    2) ? "justify-content-start" : "justify-content-around";
            $topHelpers = $homeManager->topHelpers();

            return $this->twig->render('Home/homeLogged.html.twig', [
                    'firstname' => $_SESSION['Auth']['firstname'],
                    'rendezVous' => $myAppointments,
                    'ilsOntBesoin' => $peopleInNeed,
                    'dernieresAnnonces' => $lastPost,
                    'derniersArticles' => $lastArticle,
                    'alignement' => $alignement,
                    'alignementLast' => $alignementLast,
                    'alignementBlog' => $alignementBlog,
                    'topHelpers' => $topHelpers,
            ]);
        } else {
            return $this->twig->render('Home/index.html.twig');
        }
    }

    public function faq()
    {
        return $this->twig->render('Home/faq.html.twig');
    }

    public function theyNeedYou()
    {
        $homeManager = new HomeManager();
        $peopleInNeed = $homeManager->peopleInNeed('post', $_SESSION['Auth']['id']);
        $alignement = (count($peopleInNeed)>    3) ? "justify-content-start" : "justify-content-around";
        return $this->twig->render('Home/theyNeedYou.html.twig', [
            'ilsOntBesoin' => $peopleInNeed,
            'alignement' => $alignement,
            ]);
    }

    private function trunc(array $tab)
    {
        foreach ($tab as $key => $value) {
            $tab[$key]['bodytext'] = substr($value['bodytext'], 0, 150)."… <i>(lire la suite)</i>";
        }
        return $tab;
    }
}
