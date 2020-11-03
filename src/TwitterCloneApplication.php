<?php

class TwitterCloneApplication extends Application
{
    protected $login_action = ['account', 'signin'];

    public function getRootDir(): string
    {
        return __DIR__;
    }

    protected function registerRoutes(): array
    {
        return [
            '/' => [
                'controller' => 'status',
                'action' => 'index'
            ],
            '/status/post' => [
                'controller' => 'status',
                'action' => 'post'
            ],
            '/user/:user_name' => [
                'controller' => 'status',
                'action' => 'user'
            ],
            '/user/:user_name/status/:id' => [
                'controller' => 'status',
                'action' => 'show'
            ],
            '/account' => [
                'controller' => 'account',
                'action' => 'index'
            ],
            '/account/:action' => [
                'controller' => 'account'
            ],
            '/follow' => [
                'controller' => 'account',
                'action' => 'follow'
            ],
        ];
    }

    protected function configure(): void
    {
        $this->db_manager->connect('master', [
            'dsn' => 'mysql:dbname=database;host=db',
            'user' => 'docker',
            'password' => 'docker'
        ]);
    }
}
