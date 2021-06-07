<?php
    $sectionName = $this->name !== '' ? "id=\"" .str_replace(' ', '_', $this->name) . "\"" : '';
?>
<main <?=$sectionName;?> class="<?=$view->css_class;?>" <?=$view->attributes;?> ><?=$view->value;?></main>