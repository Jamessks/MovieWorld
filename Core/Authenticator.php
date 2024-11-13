<?php

namespace Core;

use Core\RedisManagement\RedisManager;

class Authenticator
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new RedisManager();
    }

    public function attempt($email, $password)
    {
        $user = App::resolve(Database::class)
            ->query('SELECT id, username, email, password FROM users WHERE email = :email', [
                'email' => $email
            ])->find();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->login([
                    'email' => $email,
                    'username' => $user['username'],
                    'id' => $user['id']
                ]);

                return true;
            }
        }

        return false;
    }

    public function login($user)
    {
        $sessionId = session_id();

        // Check if the user already has an active session
        $existingSession = $this->redis->getUserSession($user['id']);

        if ($existingSession) {
            $this->denyLogin();
            exit;
        }

        $sessionCreated = $this->redis->setUserSession($user['id'], $sessionId);

        if (!$sessionCreated) {
            $this->denyLogin();
            exit;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
        ];

        session_regenerate_id(true);
    }

    public function denyLogin()
    {
        Session::flash('notification', 'An error occured while trying to log in.');
        redirect('/login');
        exit;
    }

    public function forceLogout()
    {
        if (isset($_SESSION['user']['id'])) {
            $this->redis->removeUserSession($_SESSION['user']['id']);
        }
        Session::destroy();

        session_start();
        session_regenerate_id(true);
        Session::flash('notification', 'Sorry, you were logged out for inactivity.');
        redirect('/');
        exit;
    }

    public function logout()
    {
        $this->redis->removeUserSession($_SESSION['user']['id']);
        Session::destroy();
    }
}
