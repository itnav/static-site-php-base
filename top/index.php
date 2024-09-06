<?php
// NOTE: 本ファイルは /index.php から参照されるため、相対パスは ../ ではなく ./ となる。
require_once './_/core.php';

teleport_style('head', path_resolve(__DIR__, './style.min.css'));
?>

<?php setup_teleporter() ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php render('./_/layouts/head.php') ?>
</head>

<body id="layout-body">
    <?php render('./_/layouts/header/header.php') ?>

    <main id="layout-main">
        <h1 class="title">Home</h1>

        <?php render('./_/components/button/button.php', array('label' => 'Count')) ?>
    </main>

    <?php render('./_/layouts/footer/footer.php') ?>
</body>

</html>

<?php flush_teleporter() ?>