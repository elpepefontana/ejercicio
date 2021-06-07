<?php
    $content = !empty($this->title) ? "<h3>{$this->title}<h3>" : "";
    $content .= "<p>{$this->content}</p>";
    return $content;
?>
