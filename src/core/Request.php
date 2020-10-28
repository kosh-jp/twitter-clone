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
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Retrieve base url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        $request_uri = $this->getRequestUri();
        $script_name = $_SERVER['SCRIPT_NAME'];

        // included script file name in url
        if (0 === strpos($request_uri, $script_name)) {
            return $script_name;
        }
        // prefix match script file directory with url
        $script_directory = dirname($script_name);
        if (0 === strpos($request_uri, $script_directory)) {
            return rtrim($script_directory, '/');
        }

        return '';
    }

    /**
     * Retrieve url path(behind base url)
     *
     * @return string
     */
    public function getPathInfo(): string
    {
        $request_uri = $this->getRequestUri();
        $base_url = $this->getBaseUrl();

        $query_position = strpos($request_uri, '?');
        if (false !== $query_position) {
            $request_uri = substr($request_uri, 0, $query_position);
        }

        $path_info = substr($request_uri, strlen($base_url));

        return (string)$path_info;
    }
}
