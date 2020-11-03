<?php

class FollowingRepository extends DbRepository
{
    /**
     * @param string $user_id
     * @param string $following_id
     * @return void
     */
    public function insert(string $user_id, string $following_id): void
    {
        $sql = "INSERT INTO following VALUES(:user_id, :following_id)";

        $stmt = $this->execute($sql, [
            ':user_id' => $user_id,
            ':following_id' => $following_id
        ]);
    }

    /**
     * @param string $user_id
     * @param string $following_id
     * @return bool
     */
    public function isFollowing(string $user_id, string $following_id): bool
    {
        $sql = "SELECT count(user_id) as count
            FROM following
            WHERE user_id = :user_id
                AND following_id = :following_id";

        $row = $this->fetch($sql, [
            'user_id' => $user_id,
            'following_id' => $following_id
        ]);

        if ($row['count'] > 0) {
            return true;
        }

        return false;
    }
}
