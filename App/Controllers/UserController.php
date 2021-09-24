<?php

namespace App\Controllers;

use App\Models\User;
use App\System\Controller;

class UserController extends Controller
{

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
    public function profile()
    {
        $this->showView('user/profile', ['profile' => 'Profile']);
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
        $user = new User();
        $user->insert(['name' => 'testmest', 'city' => 'dimitrovgrad']);

        $this->showView('user/add-user', ['addUser' => 'Add User']);
    }

    /**
     * @throws \Exception
     */
    public function updateUser()
    {
        $user = new User();
        $user->update(['name' => 'test update 1', 'city' => 'test city 1', 'id' => 7], ['id' => 7]);

        $this->showView('user/update-user', ['updateUser' => 'Update User']);
    }

    /**
     * @throws \Exception
     */
    public function deleteUser()
    {
        $user = new User();
        $user->delete(['id' => 6]);

        $this->showView('user/delete-user', ['deleteUser' => 'Delete User']);
    }
}