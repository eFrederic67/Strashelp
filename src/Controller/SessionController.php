<?php


namespace App\Controller;

use App\Model\SessionManager;

class SessionController extends AbstractController
{

    public function login()
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

                header("location:/");//.$_SERVER['REQUEST_URI']);
                exit;
            } else {
                $message = "les identifiants ne sont pas reconnus";
            }
        } else {
            $resultats = $loginManager->login('');
        }

        return $this->twig->render('Session/login.html.twig', ['message' => $message]);
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
        var_dump($_POST);

        $signUpManager = new SessionManager();
        $errors = $signUpManager->testErrorInForm($_POST);
        var_dump($errors);
        return $this->twig->render('Session/signup.html.twig', [
            'post' => $_POST,
            'errors' => $errors
        ]);
    }

    public function recovery()
    {
        return $this->twig->render('Session/recovery.html.twig');
    }

}
