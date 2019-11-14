<?php

namespace App\Controller;

use App\Model\ContactManager;

class ContactController extends AbstractController
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
    public function selectUser(int $id)
    {
        $contactManager = new ContactManager();
        $user = $contactManager->selectUserById($id);

        return $this->twig->render('Contact/contact.html.twig', ['user' => $user]);
    }
}
