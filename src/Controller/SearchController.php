<?php
namespace App\Controller;

use App\Model\SearchManager;

class SearchController extends AbstractController
{
    public function search()
    {
        $searchManager = new SearchManager();
        $search = $searchManager->search();

        return $this->twig->render(
            'Search/search.html.twig',
            [
                'search'=> $search
            ]
        );
    }

    public function addPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $itemManager = new SearchManager();
            $bazar = $itemManager->addPost($_POST);
            return $this->twig->render(
                'Search/add.html.twig',
                [
                    'bazar'=> $bazar
                ]
            );
        } else {
            var_dump($_POST);
            $categoryManager = new SearchManager();
            $category = $categoryManager->displayCategory();
            return $this->twig->render(
                'Search/add.html.twig',
                [
                   'cname' => $category
                ]
            );
        }
    }

    public function posts(int $id):string
    {
        $itemManager = new SearchManager();
        $item = $itemManager->post($id);
        return $this->twig->render(
            'Search/post.html.twig',
            ['item' => $item]
        );
    }
}
