<?php

class ClassLoader
{
    /** @var array<string> */
    protected $dirs;

    /**
     * Registers this instance as an autoloader.
     *
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Register the directory to load class
     *
     * @param string $dir
     * @return void
     */
    public function registerDir(string $dir): void
    {
        $this->dirs[] = $dir;
    }

    /**
     * Call require method
     *
     * @param string $class
     * @return void
     */
    public function loadClass(string $class): void
    {
        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (is_readable($file)) {
                require $file;

                return;
            }
        }
    }
}
