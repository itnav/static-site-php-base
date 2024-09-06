<?php
require_once '../_/core.php';

teleport_style('head', path_resolve(__DIR__, './style.min.css'));
teleport_script('head', path_resolve(__DIR__, './script.js'), array('defer' => ''));
?>

<?php setup_teleporter() ?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php render('../_/layouts/head.php', array(
        'title' => 'About',
        'description' => 'This is a about page.'
    )) ?>
</head>

<body id="layout-body">
    <?php render('../_/layouts/header/header.php') ?>

    <main id="layout-main">
        <h1 class="title">About</h1>

        <?php render('../_/components/button/button.php', array('label' => 'Count')) ?>
    </main>

    <?php render('../_/layouts/footer/footer.php') ?>
</body>

</html>

<?php flush_teleporter() ?>