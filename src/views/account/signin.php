<?php

/** @var View $this */
$this->setLayoutVar('title', 'ログイン');
?>
<h2>ログイン</h2>

<p>
    <a href="<?= $base_url ?>/account/signup">ユーザー登録</a>
</p>

<form action="<?= $base_url ?>/account/authenticate" method="post">
    <input type="hidden" name="_token" value="<?= $this->escape($_token) ?>">

    <?= $this->render('errors', ['errors' => $errors ?? []]) ?>

    <?= $this->render('account/inputs', compact('user_name', 'password')) ?>

    <p>
        <input type="submit" value="ログイン">
    </p>

</form>
