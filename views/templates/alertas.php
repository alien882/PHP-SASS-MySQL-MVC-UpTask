<?php foreach ($alertas as $tipo => $mensajes) {
    foreach ($mensajes as $mensaje) { ?>
        <div class="alerta <?php echo $tipo; ?>">
            <?php echo $mensaje; ?> 
        </div>
<?php }
} ?>