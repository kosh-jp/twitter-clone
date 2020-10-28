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
        return [];
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
