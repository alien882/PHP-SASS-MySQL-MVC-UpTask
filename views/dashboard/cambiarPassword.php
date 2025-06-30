<?php include_once "dashboardHeader.php"; ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . "/../templates/alertas.php"; ?>
    <a href="/perfil" class="enlace">Volver al perfil</a>
    <form class="formulario" method="POST" novalidate>
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input 
                type="password"
                name="password_actual"
                id="password_actual"
                placeholder="Escribe tu contraseña"
            >
        </div>
        <div class="campo">
            <label for="password_nuevo">Password Nuevo</label>
            <input 
                type="password"
                name="password_nuevo"
                id="password_nuevo"
                placeholder="Escribe tu nueva contraseña"
            >
        </div>
        <input type="submit" value="Guardar Cambios">
    </form>
</div>

<?php include_once "dashboardFooter.php"; ?>