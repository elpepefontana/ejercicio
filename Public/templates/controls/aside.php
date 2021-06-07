<?php
    $sectionName = $this->name !== '' ? "id=\"" .str_replace(' ', '_', $this->name) . "\"" : '';
?>
<aside <?=$sectionName;?> class="<?=$view->css_class;?>" <?=$view->attributes;?>><?=$view->value;?></aside>
