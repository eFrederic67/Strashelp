<?php
namespace App\Controller;

use App\Model\SearchManager;

class SearchController extends AbstractController
{
    public function search()
    {
        $searchManager = new SearchManager();
        $search = $searchManager->selectAll();

        return $this->twig->render(
            'Search/search.html.twig',
            [
                'search'=> $search
            ]
        );
    }

    public function date()
    {
        $searchManager = new SearchManager();
        $results = $searchManager->searchDate();
        return $this->twig->render(
            'Search/showrecentpost.html.twig',
            [
                'results'=> $results
            ]
        );
    }
}
