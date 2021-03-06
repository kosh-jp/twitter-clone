<?php

/** @var View $this */
$this->setLayoutVar('title', 'アカウント登録')
?>

<h2>アカウント登録</h2>

<form action="<?= $base_url ?>/account/register" method="post">
    <input type="hidden" name="_token" value="<?= $this->escape($_token) ?>">

    <?= $this->render('errors', ['errors' => $errors ?? []]) ?>

    <?= $this->render('account/inputs', compact('user_name', 'password')) ?>

    <p>
        <input type="submit" value="登録">
    </p>
</form>
