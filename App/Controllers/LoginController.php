<?php

namespace App\Controllers;

use App\Models\User;
use App\System\Controller;

class LoginController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index()
    {
        if(!empty($_POST)) {
            //validation on email and password

            $userModel = new User();
            $userIdAndPassword = $userModel->getUserIdAndPasswordByEmail($_POST['email']);

            if(!empty($userIdAndPassword) && password_verify($_POST['password'], $userIdAndPassword->password)) {
                $userById = $userModel->getUserById($userIdAndPassword->id);

                $_SESSION['isLoggedIn'] = true;
                $_SESSION['user']       = [
                    'id'    => $userById->id,
                    'email' => $userById->email
                ];

                $this->redirectTo('/user');
            }
        }

        $this->showView('login/index', ['login' => 'Login']);
    }

    public function logout()
    {
        session_destroy();

        $this->redirectTo('/login');
    }
}