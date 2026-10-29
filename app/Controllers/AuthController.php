<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Flash;
use App\Core\View;
use App\Repositories\UserRepository;

final class AuthController
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            redirect(url('mahasiswa'));
        }
        $this->renderLogin('', null);
    }

    public function login(): void
    {
        $username = input('username');
        $password = $_POST['password'] ?? '';
        $password = is_string($password) ? $password : '';

        if ($username === '' || $password === '') {
            $this->renderLogin($username, t('auth.required'));
            return;
        }

        if (!Auth::attempt($this->users, $username, $password)) {
            $this->renderLogin($username, t('auth.invalid'));
            return;
        }

        Flash::set('success', t('auth.welcome', ['name' => Auth::user()['nama']]));
        redirect(url('mahasiswa'));
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::set('info', t('auth.logged_out'));
        redirect(url('login'));
    }

    private function renderLogin(string $username, ?string $error): void
    {
        View::render('auth/login', [
            'title'    => t('auth.title'),
            'username' => $username,
            'error'    => $error,
        ]);
    }
}
