<?php

class UserRepository extends DbRepository
{
    /**
     * @param string $user_name
     * @param string $password
     * @return void
     */
    public function insert(string $user_name, string $password): void
    {
        $password = password_hash($password, PASSWORD_ARGON2ID);
        $now = new DateTime();
        $created_at = $now->format('Y-m-d H:i:s');

        $sql = "INSERT INTO user(user_name, password, created_at)
            VALUE (:user_name, :password, :created_at)";

        $stmt = $this->execute($sql, compact('user_name', 'password', 'created_at'));
    }

    /**
     * @param string $user_name
     * @return array<string,string>|false
     */
    public function fetchByUserName(string $user_name)
    {
        $sql = "SELECT * FROM user WHERE user_name = :user_name";

        return $this->fetch($sql, [':user_name' => $user_name]);
    }

    /**
     * @param string $user_name
     * @return bool
     */
    public function isUniqueUserName(string $user_name): bool
    {
        $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name";

        $row = $this->fetch($sql, compact('user_name'));
        if ($row['count'] === '0') {
            return true;
        }

        return false;
    }
}
