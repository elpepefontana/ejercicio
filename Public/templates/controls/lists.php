<?php
    $listName = !empty($this->name) ? "id=\"{$this->name}\" name=\"{$this->name}\"" : '';
?>
<ul <?=$listName;?> 
    data-role="list" 
    data-show-search="true" 
    data-cls-list="unstyled-list row flex-justify-center mt-4"
    data-cls-list-item="cell-sm-6 cell-md-4" class="text-center"
>
    <?=$this->value;?>
</ul>