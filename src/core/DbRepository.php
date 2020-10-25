<?php

abstract class DbRepository
{
    /** @var PDO */
    protected $con;

    /**
     * Constructor method
     * Set PDO instance
     *
     * @param PDO $con
     */
    public function __construct(PDO $con)
    {
        $this->setConnection($con);
    }

    /**
     * Set PDO instance
     *
     * @param PDO $con
     * @return void
     */
    public function setConnection(PDO $con): void
    {
        $this->con = $con;
    }

    /**
     * Retrieve an executed prepared statement
     *
     * @param string $sql
     * @param array<string,string> $params
     * @return PDOStatement
     */
    public function execute(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    /**
     * Run fetch method against the database
     *
     * @param string $sql
     * @param array<string,string> $params
     * @return array<string,string>
     */
    public function fetch(string $sql, array $params = []): array
    {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Run fetchAll method against the database
     *
     * @param string $sql
     * @param array<string,string> $params
     * @return array<string,string>
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $result = $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

        return $result ?: [];
    }
}
