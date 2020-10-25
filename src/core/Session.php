<?php

class Session
{
    /** @var bool */
    protected static $session_started = false;
    /** @var bool */
    protected static $session_id_regenerated = false;

    /**
     * Constructor method
     * Start the session storage
     */
    public function __construct()
    {
        if (!self::$session_started) {
            session_start();

            self::$session_started = true;
        }
    }

    /**
     * Set value as session storage
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Get the session value
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    /**
     * Remove the session value
     *
     * @param string $name
     * @return void
     */
    public function remove($name): void
    {
        unset($_SESSION[$name]);
    }

    /**
     * Clear all session values
     *
     * @return void
     */
    public function clear(): void
    {
        $_SESSION = [];
    }

    /**
     * Regenerate the session id
     *
     * @param boolean $destroy
     * @return void
     */
    public function regenerate($destroy = true): void
    {
        if (!self::$session_id_regenerated) {
            session_regenerate_id($destroy);

            self::$session_id_regenerated = true;
        }
    }
}
