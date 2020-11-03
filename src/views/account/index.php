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

<h3>フォロー中</h3>

<ul>
    <?php foreach ($followings as $following) : ?>
        <a href="<?= $base_url . '/user/' . $this->escape($following['user_name']) ?>">
            <?= $this->escape($following['user_name']) ?>
        </a>
    <?php endforeach ?>
</ul>
