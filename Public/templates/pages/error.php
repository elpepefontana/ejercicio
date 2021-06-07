<!DOCTYPE html>
<?php
    require_once TEMPLATE_PATH . '../headers/noadminheader.php';
?>
        <div class="pos-center">
            <div class="h-50 pt-30  text-center">
                ERROR: el recurso pedido no existe.
            </div>
            <div class="h-25 pt-20 text-center">
                <button class="button success" onclick="history.back()">Volver</button>
            </div>
        </div>
<?php 
    require_once TEMPLATE_PATH . '../footers/footer.php'; 
?>