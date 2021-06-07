<?php
    $sectionName = $view->name !== '' ? "id=\"" .str_replace(' ', '_', $view->name) . "\"" : '';
?>
<nav <?=$sectionName;?> class="<?=$view->css_class;?>" <?=$view->attributes;?> ><?=$view->value;?></nav>
