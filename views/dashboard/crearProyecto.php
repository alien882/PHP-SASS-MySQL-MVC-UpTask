<?php include_once "dashboardHeader.php"; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

    <form class="formulario" method="POST">
        <?php include_once "proyectoFormulario.php"; ?>
        <input type="submit" value="Crear Proyecto">
    </form>
</div>

<?php include_once "dashboardFooter.php"; ?>