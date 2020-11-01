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
                WHERE user.id = :user_id
                ORDER BY status.created_at DESC";

        return $this->fetchAll($sql, [
            ':user_id' => $user_id
        ]);
    }
}
