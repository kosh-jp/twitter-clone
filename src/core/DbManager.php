<?php

class DdManager
{
    /** @var array<PDO> */
    protected $connections = [];

    /**
     * Create a new PDO instance
     *
     * @param string $name
     * @param array<mixed> $params
     * @return void
     */
    public function connect(string $name, array $params): void
    {
        $params = array_merge([
            'dsn' => null,
            'user' => '',
            'password' => '',
            'option' => [],
        ], $params);

        $con = new PDO(
            $params['dsn'],
            $params['user'],
            $params['password'],
            $params['options']
        );

        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->connections[$name] = $con;
    }

    /**
     * Get the database connection
     *
     * @param string $name
     * @return PDO
     */
    public function getConnection(string $name = null): PDO
    {
        if (is_null(($name))) {
            return current($this->connections);
        }

        return $this->connections[$name];
    }
}
