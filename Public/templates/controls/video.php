<?php
    $name     = !empty($view->name) ? "id=\"<?=$view->name;?>\"" : "";
?>
<video <?=$name;?> data-role="video\"
<?php
    $content  = !empty($view->source) ? "data-src=\"{$view->source}\"" : "";
    $content .= !empty($view->logoSrc) ? "data-logo=\"{$view->logoSrc}\"" : "";
    $content .= !empty($view->logoHeight) ? "data-logo-height=\"{$view->logoHeight}\"" : "";
    $content .= !empty($view->link) ? "data-logo-target=\"{$view->link}\"" : "";
    $content .= $view->hideControls ? "data-controls-hide=\"{$view->hideControls}\"" : "";
?>
>
</video>
    