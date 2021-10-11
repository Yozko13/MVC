<?php

namespace App\Controllers;

use App\Models\User;
use App\System\AuthTrait;
use App\System\Controller;

/**
 * Class RegisterController
 */
class RegisterController extends Controller
{
    use AuthTrait;

    /**
     * @throws \Exception
     */
    public function index()
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
                $getUserId = $user->getUserIdAndPasswordByEmail($_POST['email']);

                $_SESSION['isLoggedIn'] = true;
                $_SESSION['user']       = [
                    'id'    => $getUserId['id'],
                    'email' => $_POST['email']
                ];

                $this->redirectTo('/user');
            }
        }

        $this->showView('register/index', ['register' => 'Register']);
    }
}