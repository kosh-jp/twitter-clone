<?php

/** @var View $this */
$this->setLayoutVar('title', $user['user_name']);
?>

<h2><?= $this->escape($user['user_name']) ?></h2>

<?= $this->render('status/status', ['statuses' => $statuses ?? []]) ?>
