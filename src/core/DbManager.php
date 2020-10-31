<?php

class DbManager
{
    /** @var array<PDO> */
    protected $connections = [];
    /** @var array<string,string> */
    protected $repository_connection_map = [];
    /** @var array<DbRepository> */
    protected $repositories = [];

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
            'options' => [],
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

    /**
     * Set a repository name
     *
     * @param string $repository_name
     * @param string $name
     * @return void
     */
    public function setRepositoryConnectionMap(string $repository_name, string $name): void
    {
        $this->repository_connection_map[$repository_name] = $name;
    }

    /**
     * Get the database connection each repository
     *
     * @param string $repository_name
     * @return PDO
     */
    public function getConnectionForRepository(string $repository_name): PDO
    {
        if (empty($this->repository_connection_map[$repository_name])) {
            return $this->getConnection();
        }

        $name = $this->repository_connection_map[$repository_name];
        $con = $this->getConnection($name);

        return $con;
    }

    /**
     * Get db repository
     *
     * @param string $repository_name
     * @return DbRepository
     */
    public function get(string $repository_name): DbRepository
    {
        if (isset($this->repositories[$repository_name])) {
            return $this->repositories[$repository_name];
        }

        $repository_class = $repository_name . 'Repository';
        $con = $this->getConnectionForRepository($repository_name);
        $repository = new $repository_class($con);
        $this->repositories[$repository_name] = $repository;

        return $this->repositories[$repository_name];
    }

    /**
     * Destruct method
     * Disconnect pdo connections
     */
    public function __destruct()
    {
        foreach ($this->repositories as $repository) {
            unset($repository);
        }

        foreach ($this->connections as $con) {
            unset($con);
        }
    }
}
