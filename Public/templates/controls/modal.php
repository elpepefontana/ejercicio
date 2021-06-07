<?php
    $contentAttributes = empty($view->contentAttributes) ? "style=\"clear: both;overflow-x: auto;white-space: nowrap;\"" : $view->contentAttributes;
    $width    = !empty($view->width) ? "width: <?=$view->width;?>%;" : '';
?>
<div id="<?=$view->name;?>Modal" class="Modal" style="z-index:<?=$view->zIndex;?>">
    <div id="<?=$view->name;?>Content" class="Modal-content" style="<?=$width;?>">
        <div class="<?=$view->css_class;?>" style="height: 45px;font-size: 20px; font-weight: bold">
            <div id="<?=$view->name;?>ModalTitle" class="px-3 py-2 mb-3" style="float: left;width: 90%">
                <?=utf8_decode($view->title);?>
            </div>
            <div class="px-3" style="float: left;width: 10%;">
                <span id="<?=$view->name;?>Close" class="ModalClose" onclick="closeModal('<?=$view->name;?>', <?=$view->closeObject;?>)">&times;</span><p>&nbsp;</p>
        </div>
    </div>
    <div id="<?=$view->name;?>ModalContent" class="<?=$view->contentClass;?>" <?=$contentAttributes;?>>
        <?=$view->content;?>
    </div>
    </div>
</div>
