<?php

class StatusRepository extends DbRepository
{
    /**
     * @param string $user_id
     * @param string $body
     * @return void
     */
    public function insert(string $user_id, string $body): void
    {
        $now = new DateTime();
        $created_at = $now->format('Y-m-d H:i:s');

        $sql = "INSERT INTO status(user_id, body, created_at)
            VALUE (:user_id, :body, :created_at)";

        $stmt = $this->execute($sql, [
            ':user_id' => $user_id,
            ':body' => $body,
            ':created_at' => $created_at
        ]);
    }

    /**
     * @param string $user_id
     * @return array<string,string>
     */
    public function fetchAllPersonalArchivesByUserId(string $user_id): array
    {
        $sql = "SELECT status.*, user.user_name
                FROM status
                    LEFT JOIN user ON status.user_id = user.id
                    LEFT JOIN following ON following.following_id = user.id
                WHERE user.id = :user_id OR following.user_id = :user_id
                ORDER BY status.created_at DESC";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }

    /**
     * @param string $user_id
     * @return array<string,string>
     */
    public function fetchAllByUserId(string $user_id): array
    {
        $sql = "SELECT status.*, user.user_name
                FROM status
                    LEFT JOIN user ON status.user_id = user.id
                WHERE user.id = :user_id
                ORDER BY status.created_at DESC";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }

    /**
     * @param string $id
     * @param string $user_name
     * @return array<string,string>
     */
    public function fetchByIdAndUserName(string $id, string $user_name): array
    {
        $sql = "SELECT status.*, user.user_name
                FROM status
                    LEFT JOIN user ON status.user_id = user.id
                WHERE status.id = :id
                    AND user.user_name = :user_name";

        return $this->fetch($sql, [
            ':id' => $id,
            ':user_name' => $user_name
        ]);
    }
}
