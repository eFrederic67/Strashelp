<?php

namespace App\Controller;

use App\Model\SearchuserManager;
use App\Model\SessionManager;

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

    public function editUser(int $id): string
    {
        $editManager = new SearchuserManager();
        $edit = $editManager->selectOneById($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data =[];
            foreach ($_POST as $key => $value) {
                $data[$key] = trim($value);
            }
            if ($_FILES['fichier']['name'] !== '') {
                $editAvManager = new SessionManager();
                $addressAvatar = $editAvManager->testImage('avatars');
                $_POST['avatar'] = "/".$addressAvatar;
            }

            $edit['email'] = $data['email'];
            $edit['login'] = $data['login'];
            $edit['adresse_1'] = $data['adresse_1'];
            $edit['adresse_2'] = $data['adresse_2'];
            $edit['phone'] = $data['phone'];
            $edit['description'] = $data['description'];

            $editManager->modifUser($id);
            header('Location: /Profile/profile/'.$id);
        }

        return $this->twig->render(
            'Profile/edit.html.twig',
            [
                'session'=>$edit,
            ]
        );
    }

    public function delUser(int $id)
    {
        $beastManager = new SearchuserManager();
        $beastManager->deleteOneUser($id);

        header('Location: /SearchUser/users');
    }
}
