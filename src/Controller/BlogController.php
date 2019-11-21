<?php


namespace App\Controller;

use App\Model\BlogManager;
use spec\GrumPHP\Task\Git\BlacklistSpec;

class BlogController extends AbstractController
{
    public function liste()
    {
        $blogManager = new BlogManager();
        $blogListe = $blogManager->selectAll();
        $blogListe = $this->trunc($blogListe); // réduit la taille de l'article à une preview
        return $this->twig->render('Blog/liste.html.twig', ['articles' => $blogListe]);
    }

    public function article($id)
    {
        $blogManager = new BlogManager();
        $article = $blogManager->selectOneById($id);
        return $this->twig->render('Blog/article.html.twig', ['article' => $article]);
    }

    public function addArticle()
    {
        $blogManager = new BlogManager();
        $category = $blogManager->displayCategory();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            var_dump($_POST);
            var_dump($_FILES);
        } else {
            return $this->twig->render('Blog/addArticle.html.twig', [
                'cname'=>$category,
            ]);
        }
    }

    private function trunc(array $tab)
    {
        foreach ($tab as $key => $value) {
            $tab[$key]['bodytext'] = substr($value['bodytext'], 0, 150)."… <i>(lire la suite)</i>";
        }
        return $tab;
    }
}
