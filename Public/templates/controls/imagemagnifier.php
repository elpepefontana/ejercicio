<?php
    $path = strtolower($this->path);
?>
<div class="imagemagnifier <?=$view->css_class;?>" data-magnifier-mode="glass" data-lens-type="circle" data-lens-size="200" <?=$view->attributes;?> >
    <img class="h-100" src="<?=$path . $this->name;?>" >
</div>