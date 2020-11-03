<?php

class UserRepository extends DbRepository
{
    /**
     * @param string $user_name
     * @param string $password
     * @return bool
     */
    public function insert(string $user_name, string $password): bool
    {
        $password = password_hash($password, PASSWORD_ARGON2ID);
        if (empty($password)) {
            return false;
        }
        $now = new DateTime();
        $created_at = $now->format('Y-m-d H:i:s');

        $sql = "INSERT INTO user(user_name, password, created_at)
            VALUE (:user_name, :password, :created_at)";

        $stmt = $this->execute($sql, [
            ':user_name' => $user_name,
            ':password' => $password,
            ':created_at' => $created_at,
        ]);

        if ($stmt == false) {
            return false;
        }

        return true;
    }

    /**
     * @param string $user_name
     * @return array<string,string>
     */
    public function fetchByUserName(string $user_name): array
    {
        $sql = "SELECT * FROM user WHERE user_name = :user_name";

        return $this->fetch($sql, [
            ':user_name' => $user_name
        ]);
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

    /**
     * @param string $user_id
     * @return array<string,string>
     */
    public function fetchAllFollowingsByUserId(string $user_id): array
    {
        $sql = "SELECT user.*
            FROM user
                LEFT JOIN following ON following.following_id = user.id
            WHERE following.user_id = :user_id";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }
}
