<?php
teleport_style('head', path_resolve(__DIR__, './button.min.css'));
teleport_script('head', path_resolve(__DIR__, './button.js'), array('defer' => ''));
?>

<button class="button" data-label="<?php echo $props['label'] ?>">
    &nbsp;
</button>