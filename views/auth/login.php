<div class="contenedor login">
    <?php include_once __DIR__ . "/../templates/nombreSitio.php"; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php include_once __DIR__ . "/../templates/alertas.php"; ?>

        <form method="POST" class="formulario" novalidate>
            <div class="campo">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Tu email"
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
            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/registro">¿Aún no tienes una cuenta? Crea una</a>
            <a href="/olvide-password">¿Olvidaste tu password?</a>
        </div>
    </div>
</div>