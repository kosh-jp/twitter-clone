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
            '/account' => [
                'controller' => 'account',
                'action' => 'index'
            ],
            '/account/:action' => [
                'controller' => 'account'
            ]
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
