<?php
// タイトルの設定
$default_title = isset($props['default_title']) ? $props['default_title'] : 'Static Site PHP Base';;
$raw_title = isset($props['title']) ? $props['title'] : null;
$title_template = isset($props['title_template']) ? $props['title_template'] : "%s | $default_title";
$title = $raw_title ? sprintf($title_template, $raw_title) : $default_title;

// 説明文の設定
$description = isset($props['description']) ? $props['description'] : null;
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?php echo $title ?></title>
<?php if ($description): ?>
    <meta name="description" content="<?php echo $description ?>">
<?php endif; ?>

<link rel="stylesheet" href="/_/styles/global.min.css">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">

<?php teleporter('head') ?>