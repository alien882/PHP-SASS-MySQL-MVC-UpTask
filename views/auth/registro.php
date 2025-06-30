<div class="contenedor crear">
    <?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <form method="post" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    placeholder="Tu nombre"
                    value="<?php echo $usuario->nombre; ?>"
                >
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Tu email"
                    value="<?php echo $usuario->email; ?>"
                >
            </div>
            <div class="campo">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Tu password"
                >
            </div>
            <div class="campo">
                <label for="password2">Confirmar Password</label>
                <input
                    type="password"
                    id="password2"
                    name="password2"
                    placeholder="Repite tu password"
                >
            </div>
            <input type="submit" class="boton" value="Registrarse">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
            <a href="/olvide-password">¿Olvidaste tu password?</a>
        </div>
    </div>
</div>