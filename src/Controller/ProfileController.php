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
        return $this->twig->render('Profile/profile.html.twig', ['profile' => $profile]);
    }

    public function myprofile()
    {
        $profileManager = new profileManager();
        $session = $profileManager->session();

        return $this->twig->render('Profile/myprofile.html.twig', ['session' => $session]);
    }

    public function edit(): string
    {
        $profileManager = new profileManager();
        $myprofile = $profileManager->selectAll();
        $session = $profileManager->session();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $myprofile['avatar'] = $_POST['avatar'];
            $myprofile['email'] = $_POST['email'];
            $myprofile['login'] = $_POST['login'];
            $myprofile['adresse_1'] = $_POST['adresse_1'];
            $myprofile['adresse_2'] = $_POST['adresse_2'];
            $myprofile['phone'] = $_POST['phone'];
            $myprofile['description'] = $_POST['description'];


            $profileManager->update($myprofile);
            header('Location:/profile/myprofile');
        }
        return $this->twig->render('Profile/edit.html.twig', ['session' => $session]);
    }
}
