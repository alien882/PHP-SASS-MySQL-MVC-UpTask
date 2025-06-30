<div class="contenedor olvide">
    <?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Reestablecer Password</p>

        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <form method="post" class="formulario" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Tu email"
                >
            </div>
            <input type="submit" class="boton" value="Enviar Instrucciones">
        </form>

        <div class="acciones">
            <a href="/registro">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/">¿Ya tienes una cuenta? Iniciar Sesión</a>
        </div>
    </div>
</div>