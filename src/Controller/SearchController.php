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
        $categoryManager = new SearchManager();
        if (!empty($_POST)) {
            $category = $categoryManager->displayCategory();
            $errors = [];
            if ($_POST['title'] != htmlspecialchars($_POST['title'])) {
                $errors['title'] = 'Caractères spéciaux interdit !';
            }
            foreach ($category as $key => $value) {
                $category[$key] = $value['id'];
            }
            if (!in_array($_POST['id_category'], $category)) {
                $errors['id_category'] = 'Catégorie inexistante';
            }

            if (empty($_POST['text_annoucement'])) {
                $errors['text_annoucement'] = 'Merci de remplir ce champs';
            }
            if (!is_numeric($_POST['nbmin'])) {
                $errors['nbmin'] = 1;
            }

            if (!is_numeric($_POST['nbmax'])) {
                $errors['nbmax'] = 1;
            }
            if (count($errors) > 0) {
                return $this->twig->render(
                    'Search/add.html.twig',
                    [
                        'errors' => $errors,
                        'post' => $_POST,
                        'cname' => $categoryManager->displayCategory()
                    ]
                );
            } else {
                $categoryManager->addPost($_POST);
                $getEntry = $categoryManager->getLastEntry();
                header('Location: /Search/posts/'. $getEntry['id']);
            }
        } else {
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
        return $this->twig->render('Search/post.html.twig', ['item' => $item,]);
    }

    public function editPost(int $id): string
    {
        $editManager = new SearchManager();
        $edit = $editManager->selectOneById($id);
        $category = $editManager->displayCategory();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST as $key => $value) {
                $data[$key] = trim($value);
            }

            $edit['title'] = $data['title'];
            $edit['type'] = $data['type'];
            $edit['id_category'] = $data['id_category'];
            $edit['start_hour'] = $data['start_hour'];
            $edit['end_hour'] = $data['end_hour'];
            $edit['date_publication'] = $data['date_publication'];
            $edit['text_annoucement'] = $data['text_annoucement'];
            $edit['nbmin'] = $data['nbmin'];
            $edit['nbmax'] = $data['nbmax'];

            $editManager->modifPost($id);
            header('Location: /search/posts/'.$id);
        }

        return $this->twig->render(
            'Search/add.html.twig',
            [
                'post'=>$edit,
                'cname'=>$category
            ]
        );
    }

    public function delPost(int $id)
    {
        $beastManager = new SearchManager();
        $beastManager->deleteOnePost($id);

        header('Location: /search/search');
    }

}
