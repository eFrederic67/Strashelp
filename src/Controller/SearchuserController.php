<?php

namespace App\Controller;

use App\Model\SearchuserManager;

class SearchuserController extends AbstractController
{
    public function search($id)
    {
        $searchManager = new SearchuserManager();
        $search = $searchManager->search($id);

        return $this->twig->render('Searchuser/user.html.twig', ['search'=> $search]);
    }

    public function users()
    {
        $userManager = new SearchuserManager();
        $user = $userManager->displayUsers();
        return $this->twig->render('Searchuser/user.html.twig', ['users' => $user]);
    }
}
