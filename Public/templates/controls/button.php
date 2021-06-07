<?php
    $buttonName  = !empty($view->name) ? "id=\"{$view->name}\" name=\"{$view->name}\"" : '';
    $buttonClass = !empty($view->css_class) ? "class=\"button {$view->css_class}\"" : 'class="button"';
    $onClick = !empty($view->onclick) ? "onclick=\"{$view->onclick}\"" : "";
?>
<button <?=$buttonName;?> <?=$buttonClass;?> <?=$view->attributes;?> <?=$onClick;?> > <?=$view->content;?></button>
    