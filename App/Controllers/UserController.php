<?php

namespace App\Controllers;

use App\Models\User;
use App\System\AuthTrait;
use App\System\Controller;

class UserController extends Controller
{
    use AuthTrait;

    public function __construct()
    {
        if(!$this->isLoggedIn()) {
            $this->redirectTo('/login');
        }
    }

    /**
     * @throws \Exception
     */
    public function index()
    {
        $city = [];
        $user = new User();

        $this->showView('user/index', [
            'main'  => 'Index Users',
            'users' => $user->getAll($city)
        ]);
    }

    /**
     * @throws \Exception
     */
    public function profile($id)
    {
        $user     = new User();
        $currUser = $user->getUserById($id);

        $this->showView('user/profile', [
            'profile' => 'Profile',
            'user'    => $currUser
        ]);
    }

    /**
     * @throws \Exception
     */
    public function profileSettings()
    {
        $this->showView('user/profile-settings', ['settings' => 'Profile Settings']);
    }

    /**
     * @throws \Exception
     */
    public function addUser()
    {
        if(!empty($_POST)) {
//            $validated = $request->validate([
//                'title' => 'required|unique:posts|max:255',
//                'body' => 'required',
//            ], [
//                'title.required' => 'sadsad'
//            ]);

            $data = $_POST;
            $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $user = new User();

            if($user->insert($data)) {
                $this->redirectTo('/user');
            }
        }

        $this->showView('user/add-user', ['addUser' => 'Add User']);
    }

    /**
     * @throws \Exception
     */
    public function updateUser($id)
    {
        $user       = new User();
        $chosenUser = $user->getUserById($id);

        if(!empty($_POST)) {
            $user->update($_POST, ['id' => $id]);

            $chosenUser = $user->getUserById($id);
        }

        $this->showView('user/update-user', [
            'updateUser' => 'Update User',
            'chosenUser' => $chosenUser
        ]);
    }

    /**
     * @throws \Exception
     */
    public function deleteUser($id)
    {
        $user = new User();
        $user->delete(['id' => $id]);

        $this->redirectTo('/user');
    }
}