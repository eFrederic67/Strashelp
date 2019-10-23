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
     * Display profile edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id): string
    {
        $profileManager = new profileManager();
        $profile = $profileManager->selectOneById($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profile['title'] = $_POST['title'];
            $profileManager->update($profile);
        }

        return $this->twig->render('profile/edit.html.twig', ['profile' => $profile]);
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
}
