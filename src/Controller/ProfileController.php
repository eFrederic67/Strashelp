<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ProfileManager;
use App\Model\SearchManager;
use App\Model\SessionManager;
use http\Header;

/**
 * Class profileController
 *
 */
class ProfileController extends AbstractController
{


    /**
     * Display profile listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $profileManager = new profileManager();
        $profiles = $profileManager->selectAll();

        return $this->twig->render('Profile/index.html.twig', ['profiles' => $profiles]);
    }


    /**
     * Display profile informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $profileManager = new profileManager();
        $profile = $profileManager->selectOneById($id);

        return $this->twig->render('Profile/profile.html.twig', ['profile' => $profile]);
    }


    /**
     * Handle profile deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        $profileManager = new profileManager();
        $profileManager->delete($id);
        header('Location:/profile/index');
    }

    public function profile(int $id)
    {
        if ($id == $_SESSION['Auth']['id']) {
            header('Location:/profile/myprofile');
        } else {
            $profileManager = new profileManager();
            $profile = $profileManager->selectOneById($id);
            $searchManager = new SearchManager();
            $search = $searchManager->search();
            $skills = $profileManager->skill($profile);
            $annonces = $profileManager->annonces($profile);
            return $this->twig->render(
                'Profile/profile.html.twig',
                ['profile' => $profile, 'skills' => $skills, 'annonces' => $annonces, 'search' => $search]
            );
        }
    }

    public function myprofile():string
    {
        $profileManager = new profileManager();
        $myprofile = $profileManager->session();
        $searchManager = new SearchManager();
        $search = $searchManager->search();
        $skills = $profileManager->skill($myprofile);
        $annonces = $profileManager->annonces($myprofile);

        return $this->twig->render(
            'Profile/myprofile.html.twig',
            [
                'myprofile' => $myprofile,
                'skills' => $skills,
                'annonces' => $annonces,
                'search' => $search
            ]
        );
    }

    public function edit()
    {
        $profileManager = new profileManager();
        $session = $profileManager->session();
        $signUpManager = new SessionManager();
        $category = $signUpManager->displayCategory();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = $profileManager->testErrorInForm($_POST, $session);
            $profileManager->testCompetence();
            if ($_FILES['fichier']['name'] !== '') {
                $addressAvatar = $signUpManager->testImage();
                $_POST['avatar'] = "/".$addressAvatar;
            }
            if (count($errors) == 0) {
                if (isset($_POST['password']) && $_POST['password'] != "") {
                    $_POST['password'] = sha1($_POST['password']);
                }
                if ($profileManager->update($_POST, $session)) {
                    if ($_POST['password'] != $_SESSION['Auth']['pass'] && $_POST['password'] != "") {
                        $_SESSION['Auth']['pass'] = $_POST['password'];
                    }
                    $_SESSION['Auth']['login'] = $_POST['login'];
                    header("Location:/Profile/myprofile");
                }
            } else {
                return $this->twig->render(
                    'Profile/edit.html.twig',
                    [
                        'session' => $session,
                        'errors' => $errors,
                        'category' => $category,
                    ]
                );
            }
        } else {
            return $this->twig->render(
                'Profile/edit.html.twig',
                [
                    'session' => $session,
                    'category' => $category,
                ]
            );
        }
    }
}
