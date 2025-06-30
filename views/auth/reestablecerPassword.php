<div class="contenedor reestablecer">
    <?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <?php if($mostrar) { ?>
            <form method="post" class="formulario">
                <div class="campo">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Nuevo password"
                >
                </div>
                <input type="submit" class="boton" value="Guardar Password">
            </form>
        <?php } ?>

        <div class="acciones">
            <a href="/registro">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
        </div>
    </div>
</div>