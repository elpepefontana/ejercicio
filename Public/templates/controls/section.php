<?php
    $sectionName = $this->name !== '' ? "id=\"" .str_replace(' ', '_', $this->name) . "\"" : '';
?>
<section <?=$sectionName;?> class="<?=$this->css_class;?>" <?=$this->attributes;?> ><?=$this->value;?></section>