<?php

/** @var View $this */
$this->setLayoutVar('title', 'ホーム');
?>

<h2>ホーム</h2>

<form action="<?= $base_url ?>/status/post" method="post">
    <input type="hidden" name="_token" value="<?= $this->escape($_token) ?>">

    <?= $this->render('errors', ['errors' => $errors ?? []]) ?>

    <textarea name="body" cols="60" rows="2"><?= $this->escape($body) ?></textarea>

    <p>
        <input type="submit" value="ツイート">
    </p>
</form>

<?= $this->render('status/status', ['statuses' => $statuses ?? []]) ?>
