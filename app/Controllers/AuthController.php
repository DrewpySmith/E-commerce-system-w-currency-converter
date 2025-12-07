<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel; // Ensure you have this or use Builder

class AuthController extends Controller
{
    public function index()
    {
        helper(['form']);
        return view('login');
    }

    public function auth()
    {
        $session = session();
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $user = $builder->where('email', $email)->get()->getRowArray();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $ses_data = [
                    'id'       => $user['id'],
                    'username' => $user['username'],
                    'email'    => $user['email'],
                    'role'     => $user['role'],
                    'isLoggedIn' => true
                ];
                $session->set($ses_data);
                return redirect()->to('/');
            } else {
                $session->setFlashdata('error', 'Invalid password.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email not found.');
            return redirect()->to('/login');
        }
    }

    // --- NEW REGISTRATION METHODS ---

    public function register()
    {
        helper(['form']);
        return view('register');
    }

    public function store()
    {
        helper(['form']);
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $rules = [
            'username'      => 'required|min_length[3]|max_length[50]',
            'email'         => 'required|min_length[6]|max_length[100]|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[6]|max_length[255]',
            'confpassword'  => 'matches[password]'
        ];

        if ($this->validate($rules)) {
            $data = [
                'username' => $this->request->getVar('username'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role'     => 'user' // Default role
            ];
            
            $builder->insert($data);
            
            return redirect()->to('/login');
        } else {
            return view('register', ['validation' => $this->validator]);
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}