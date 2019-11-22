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
            // uploading de la photo
            if ($_FILES['fichier']['name']) {
                $addressImage = $blogManager->testImage('blog');
                $_POST['image'] = "/".$addressImage;
            } else {
                switch ($_POST['id_category']) {
                    case 1:
                        $_POST['image'] = "/assest/images/Default_Bricolage.jpg";
                        break;
                    case 2:
                        $_POST['image'] = "/assest/images/Default_Cuisine.jpg";
                        break;
                    case 3:
                        $_POST['image'] = "/assest/images/Default_Education.jpg";
                        break;
                }
            }

            if ($blogManager->insertInDB($_POST)) {
                $id = $blogManager->getLastEntry('article');
                header('location:/Blog/article/'.$id['id']);
            }
        } else {
            return $this->twig->render('Blog/addArticle.html.twig', [
                'cname'=>$category,
                'titre' => 'Créer'

            ]);
        }
    }

    public function listeGestion()
    {
        $blogManager = new BlogManager();
        $blogs = $blogManager->getAllJoined();
        return $this->twig->render('Blog/listeGestion.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    public function delArticle($id)
    {
        $blogManager = new BlogManager();

        $blogManager->delArticle($id);

        $blogs = $blogManager->getAllJoined();
        return $this->twig->render('Blog/listeGestion.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    public function editArticle($id)
    {
        $blogManager = new BlogManager();
        $category = $blogManager->displayCategory();
        $post = $blogManager->selectOneById($id);
        return $this->twig->render('Blog/addArticle.html.twig', [
            'cname'=>$category,
            'post'=>$post,
            'titre' => 'Modifier'
        ]);
    }


    private function trunc(array $tab)
    {
        foreach ($tab as $key => $value) {
            $tab[$key]['bodytext'] = substr($value['bodytext'], 0, 150)."… <i>(lire la suite)</i>";
        }
        return $tab;
    }
}
