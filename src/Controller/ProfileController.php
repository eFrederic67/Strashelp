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
     * Display profile creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profileManager = new profileManager();
            $profile = [
                'title' => $_POST['title'],
            ];
            $id = $profileManager->insert($profile);
            header('Location:/profile/show/' . $id);
        }

        return $this->twig->render('profile/add.html.twig');
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $signUpManager = new SessionManager();
            $errors = $profileManager->testErrorInForm($_POST, $session);
            if (isset($_FILES['fichier']['name']) && $_FILES['fichier']['name'] != $session['avatar']) {
                $addressAvatar = $signUpManager->testImage();
                $_POST['avatar'] = "/".$addressAvatar;
            }

            if (count($errors) == 0) {
                if ($profileManager->update($_POST)) {
                    header("Location:/Profile/myprofile");
                }
            } else {
                return $this->twig->render(
                    'Profile/edit.html.twig',
                    [
                        'session' => $session,
                        'errors' => $errors,
                    ]
                );
            }
        } else {
            return $this->twig->render(
                'Profile/edit.html.twig',
                [
                    'session' => $session,
                ]
            );
        }
    }
}
