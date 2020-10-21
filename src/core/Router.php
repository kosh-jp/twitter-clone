<?php

class Route
{
    /** @var array<string,array> */
    protected $routes;

    /**
     * Constructor
     *
     * @param array<string,array> $definitions
     */
    public function __construct($definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }

    /**
     * Convert dynamic parameter to regular expressions
     *
     * @param array<string,array> $definitions
     * @return array<string,array>
     */
    public function compileRoutes($definitions): array
    {
        $routes = [];

        foreach ($definitions as $url => $params) {
            $tokens = explode('/', ltrim($url, '/'));
            foreach ($tokens as $index => $token) {
                if (0 === strpos($token, ':')) {
                    $name = substr($token, 1);
                    $token = '(?P<' . $name . '>[^/]+)';
                }
                $tokens[$index] = $token;
            }

            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;
        }

        return $routes;
    }

    /**
     * Return routes params
     *
     * @param string $path_info
     * @return array<string,string>|false
     */
    public function resolve($path_info)
    {
        if ('/' !== substr($path_info, 0, 1)) {
            $path_info = '/' . $path_info;
        }

        foreach ($this->routes as $pattern => $params) {
            if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                $params = array_merge($params, $matches);

                return $params;
            }
        }

        return false;
    }
}
