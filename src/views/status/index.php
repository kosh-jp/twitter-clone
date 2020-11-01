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

<div id="statuses">
    <?php foreach ($statuses as $status) : ?>
        <div class="status">
            <div class="status_content">
                <?= $this->escape($status['user_name']) ?>
                <?= $this->escape($status['body']) ?>
            </div>
            <div>
                <?= $this->escape($status['created_at']) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
