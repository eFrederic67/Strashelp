<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\SignaluserManager;
use App\Model\SignalpostManager;

class SignalController extends AbstractController
{
    /**
     * Display item informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function signalUser(int $id)
    {
        $signaluserManager = new SignaluserManager();
        $user = $signaluserManager->selectOneById($id);

        return $this->twig->render('Signal/signaluser.html.twig', ['signaluser' => $user]);
    }
    public function signalPost(int $id)
    {
        $signalpostManager = new SignalpostManager();
        $post = $signalpostManager->selectOneById($id);

        // $signal = $signalpostManager->signalPost($id);
        return $this->twig->render('Signal/signalpost.html.twig', ['signalpost' => $post]);
    }
}
