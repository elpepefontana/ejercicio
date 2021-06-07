<?php
    $sectionName = $view->name !== '' ? "id=\"" .str_replace(' ', '_', $view->name) . "\"" : '';
?>
<article <?=$sectionName;?> class="<?=$view->class;?>" <?=$view->attributes;?>><?=$view->value;?></article>