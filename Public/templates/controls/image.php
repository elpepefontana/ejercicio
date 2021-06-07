<?php
    $imagePath = strtolower($imagePath);
    $width  = !empty($view->width) ? "width: $view->width;" : '';
    $height = !empty($view->height) ? "height: $view->height;" : '';
    $style  = !empty($width) && !empty($height) ? "style=\"{$width}{$height}\"" : '';
    
    function drawImageOverLay($view)
    {
        $content  = '<div class="image-overlay op-' . $view->overlayColor . '">';
        $content .= $view->overlay; 
        $content .= '</div>';
        return $content;
    }
?>
<div id="img_<?=$view->name;?>" class="img-container <?=$view->css_class?>" <?=$view->attributes;?> >
    <img src="<?=$imagePath . $view->name;?>" data-src="<?=$imagePath . $view->name;?>" <?=$style;?> >
    <?php !empty($view->overlay) ? drawImageOverLay($view->overlay) : ''; ?>
</div>