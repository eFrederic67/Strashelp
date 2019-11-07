<?php

namespace App\Controller;

use App\Model\SessionManager;

class SessionController extends AbstractController
{
    public function index()
    {
        $message = '';
        $loginManager = new SessionManager();

        if (!empty($_POST)) {
            $resultats = $loginManager->login($_POST);
            if (($resultats)) {
                //A faire : setcookie()
                $_SESSION['Auth'] = array(
                  'login' => $_POST['login'],
                  'pass' => sha1($_POST['password']),
                );
                header("Location:/");
            } else {
                $message = "les identifiants ne sont pas reconnus";
            }
        } else {
            $resultats = $loginManager->login('');
        }

        return $this->twig->render('Session/index.html.twig', ['message' => $message]);
    }

    public function logout()
    {
        $loginManager = new SessionManager();

        if ($loginManager->logout()) {
            header("location:/");
        }
    }

    public function signup()
    {
        if (!empty($_POST)) {
            $loginManager = new SessionManager();
            $test = $loginManager->signup($_POST);

            if ($test) { // quand il y a des erreurs
            } else { //Quand tout va bien pour l'inscription
                // il faut entrer les infos dans la base
                // puis remettre un message comme quoi tout s'est bien passé
                // et envoyer un mail de confirmation.
            }
        } else {
            return $this->twig->render('Session/signup.html.twig');
        }
    }
}
