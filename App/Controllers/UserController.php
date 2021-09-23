<?php

namespace App\Controllers;

use App\Models\User;
use App\System\Controller;

class UserController extends Controller
{


    public function index()
    {
        $city = [];
        $user = new User();

        $this->showView('user/index', [
            'main'  => 'Index Users',
            'users' => $user->getAll($city)
        ]);
    }

    public function profile()
    {
        $this->showView('user/profile', ['profile' => 'Profile']);
    }

    public function profileSettings()
    {
        $this->showView('user/profile-settings', ['settings' => 'Profile Settings']);
    }

    public function addUser()
    {
        $this->showView('user/add-user', ['addUser' => 'Add User']);
    }
}