<?php


namespace App\Controller;

use App\Model\SessionManager;

class SessionController extends AbstractController
{

    public function login()
    {
        $message = '';
        $loginManager = new SessionManager();

        if (isset($_SESSION['Auth'])) {
            header('location:/home/index');
        } else {
            if (!empty($_POST)) {
                $resultats = $loginManager->getLogin($_POST);
                if (($resultats)) {
                    $_SESSION['Auth'] = array(
                        'login' => $_POST['login'],
                        'pass' => sha1($_POST['password']),
                        'firstname' => $resultats[0]['firstname'],
                        'id' => $resultats[0]['id'],
                        'admin' => $resultats[0]['admin'],
                    );
                    header("location:/home/index");
                } else {
                    $message = "les identifiants ne sont pas reconnus";
                }
            }

            return $this->twig->render('Session/login.html.twig', ['message' => $message]);
        }
    }

    public function logout()
    {
        $loginManager = new SessionManager();
        if ($loginManager->logout()) {
            header("location: /");
        }
    }

    public function signup()
    {
        $signUpManager = new SessionManager();
        $category = $signUpManager->displayCategory();
        if (empty($_POST)) {
            // si le post est vide parce que c'est le 1er loading de la page
            return $this->twig->render('Session/signup.html.twig', [
                'category' => $category,
                'display1' => 'block',
                'display2' => 'none',
                'avatar' => "/assets/images/profil.png",
            ]);
        } else {
            // si le post est rempli parce que c'est un retour de formulaire
            // on teste les erreurs
            $signUpManager = new SessionManager();
            $errors = $signUpManager->testErrorInForm($_POST);
            if (!isset($_POST['avatar'])) {
                $_POST['avatar'] = "/assets/images/profil.png";
            }
            if ($_POST['bricolage']== 'on') {
                $_POST['bricolage']= 1;
            }
            if ($_POST['cuisine']== 'on') {
                $_POST['cuisine']= 1;
            }
            if ($_POST['éducation']== 'on') {
                $_POST['éducation']= 1;
            }
            if ($_FILES['fichier']['name'] !== '') {
                $addressAvatar = $signUpManager->testImage();
                $_POST['avatar'] = "/".$addressAvatar;
            }
            if (count($errors) == 0) {
                // s'il le teste d'erreur est ok
                // lancer les procédures pour ajouter la personne dans la base de donnée
                $_POST['birthday'] = $_POST['yearOfBirth']."-".$_POST['monthOfBirth']."-".$_POST['dayOfBirth'];
                $_POST['admin']=0;
                $_POST['password'] = sha1($_POST['password']);


                if ($signUpManager->insertInDB($_POST)) {
                    $lastUser = $signUpManager->getLastUser();
                    $_SESSION['Auth'] = array(
                        'login' => $_POST['login'],
                        'pass' => $_POST['password'],
                        'firstname' => $_POST['firstname'],
                        'id' => $lastUser['id'],
                        'admin' => 1,
                    );
                    $signUpManager->cleanPhotosTemp();
                    $idUser = $signUpManager->getLastUser();
                    header("Location:/Profile/profile/".$idUser['id']);
                }
            } else {
                // s'il y a des erreurs on reloade la page
                // en remettant les informations et envoyant les messages d'erreurs
                return $this->twig->render('Session/signup.html.twig', [
                    'category' => $category,
                    'errors' => $errors,
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
                    'description' => $_POST['description'],
                    'bricolage' => $_POST['bricolage'],
                    'cuisine' => $_POST['cuisine'],
                    'éducation' => $_POST['éducation'],
                    'display1' => 'none',
                    'display2' => 'block',

                ]);
            }
        }
    }

    public function recovery()
    {
        return $this->twig->render('Session/recovery.html.twig');
    }

    public function signUpValidate()
    {
        return $this->twig->render('Session/SignUpValidate.html.twig');
    }
}
