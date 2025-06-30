<?php include_once "dashboardHeader.php"; ?>

<?php if(count($proyectos) === 0) { ?>
    <p class="no-proyectos">
        No hay proyectos aún
        <a href="/crear-proyecto">Comienza creando uno</a>
    </p>
    
<?php } else { ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) { ?>
            <a href="/proyecto?id=<?php echo $proyecto->url; ?>">
                <li class="proyecto">    
                    <p><?php echo $proyecto->proyecto; ?></p>
                </li>
            </a>
        <?php } ?>
    </ul>
<?php } ?>

<?php include_once "dashboardFooter.php"; ?>