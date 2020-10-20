<?php

class Request
{
    /**
     * Return true if request is post method
     *
     * @return boolean
     */
    public function isPost(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }

    /**
     * Retrieve query string argument
     *
     * @param string $name
     * @param mixed $default
     * @return string|mixed
     */
    public function getGet(string $name, $default = null)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }

        return $default;
    }

    /**
     * Retrieve request body argument
     *
     * @param string $name
     * @param mixed $default
     * @return string|mixed
     */
    public function getPost(string $name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }

        return $default;
    }

    /**
     * Return the host name
     *
     * @return string
     */
    public function getHost(): string
    {
        if (!empty($_SERVER['HTTP_POST'])) {
            return $_SERVER['HTTP_POST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    /**
     * Return true if HTTPS requests
     *
     * @return boolean
     */
    public function isSsl(): bool
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }

        return false;
    }

    /**
     * Returns the requested URI (path and query string)
     *
     * @return string
     */
    public function getRequestUri(): string
    {
        return $_SERVER['REQUEST_URL'];
    }
}
