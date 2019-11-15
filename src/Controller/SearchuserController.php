<?php

namespace App\Controller;

use App\Model\SearchuserManager;

class SearchuserController extends AbstractController
{
    // TODO utiliser les examples ci-dessous

    public function search()
    {
        $searchuserManager = new SearchuserManager();
        $search = $searchuserManager->search();

        return $this->twig->render(
            'Search/user.html.twig',
            [
                'search'=> $search
            ]
        );
    }

    public function users(int $id):string
    {
        $itemManager = new SearchuserManager();
        $item = $itemManager->post($id);

        return $this->twig->render('Search/user.html.twig', ['item' => $item]);
    }
}
