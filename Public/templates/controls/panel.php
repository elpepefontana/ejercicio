<?php
    $name = $this->name !== '' ? "id=\"{$this->name}\" name=\"{$this->name}\"" : '';
?>
<div <?=$name;?> data-role="panel" <?=$this->attributes;?> <?=$this->css_class;?> > <?=$this->content;?> </div>