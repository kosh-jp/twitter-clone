<?php

/** @var View $this */
$this->setLayoutVar('title', 'アカウント');
?>
<h2>アカウント</h2>

<p>
    ユーザーID:
    <a href="<?= $base_url . '/user/' . $this->escape($user['user_name']) ?>">
        <?= $this->escape($user['user_name']) ?>
    </a>
</p>

<ul>
    <li>
        <a href="<?= $base_url ?>/">ホーム</a>
    </li>
    <li>
        <a href="<?= $base_url ?>/account/signout">ログアウト</a>
    </li>
</ul>
