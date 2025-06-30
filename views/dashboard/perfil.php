<?php include_once "dashboardHeader.php"; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . "/../templates/alertas.php"; ?>
    <a href="/cambiar-password" class="enlace">Cambiar Contraseña</a>
    <form class="formulario" method="POST" novalidate>
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input 
                type="text"
                value="<?php echo $usuario->nombre; ?>"
                name="nombre"
                id="nombre"
                placeholder="Nuevo nombre"
            >
        </div>
        <div class="campo">
            <label for="email">Email</label>
            <input 
                type="email"
                value="<?php echo $usuario->email; ?>"
                name="email"
                id="email"
                placeholder="Nuevo email"
            >
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once "dashboardFooter.php"; ?>