<?php

/** @var View $this */
$this->setLayoutVar('title', $user['user_name']);
?>

<h2><?= $this->escape($user['user_name']) ?></h2>

<?php if ($following === true) : ?>
    <p>フォロー中</p>
<?php elseif ($following === false) : ?>
    <form action="<?= $base_url ?>/follow" method="post">
        <input type="hidden" name="_token" value="<?= $this->escape($_token) ?>">
        <input type="hidden" name="following_name" value="<?= $this->escape($user['user_name']) ?>">

        <input type="submit" value="フォロー">
    </form>
<?php endif ?>

<?= $this->render('status/status', ['statuses' => $statuses ?? []]) ?>
