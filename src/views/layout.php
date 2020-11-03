<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ?  $title . '|' : '' ?>TwitterClone</title>
</head>

<body>
    <div id="header">
        <h1><a href="<?= $base_url ?>/">TwitterClone</a></h1>
    </div>

    <div id="nav">
        <p>
            <?php if ($session->isAuthenticated()) : ?>
                <a href="<?= $base_url ?>/">ホーム</a>
                <a href="<?= $base_url ?>/account">アカウント</a>
            <?php else : ?>
                <a href="<?= $base_url ?>/account/signin">ログイン</a>
                <a href="<?= $base_url ?>/account/signup">アカウント登録</a>
            <?php endif ?>
        </p>
    </div>

    <div id="main">
        <?= $_content ?>
    </div>

</body>

</html>
