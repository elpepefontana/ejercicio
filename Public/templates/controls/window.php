<div id="win_<?=$view->name;?>" class="window" data-role="window">
    <div class="window-caption">
        <?php 
            echo !empty($view->icon) ? "       <span class=\"icon {$view->icon}\"></span>" : "";
        ?>
        <span id ="win_<?=$view->name;?>Title" class="title"><?=$view->title;?></span>
        <div class="buttons">
        <?php 
            echo $view->minimize ? "           <span class=\"btn-min\"></span>" : "";
            echo $view->maximize ? "           <span class=\"btn-max\"></span>" : "";
            echo $view->close ? "           <span class=\"btn-close\"></span>" : "";
        ?>
        </div>
    </div>
    <div id="win_<?=$view->name;?>Content" class="window-content p-2">
        <?=$view->content;?>
    </div>
</div>