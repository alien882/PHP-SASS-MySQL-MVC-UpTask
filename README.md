# Proyecto PHP + SASS + MySQL + MVC de UpTask
Es una aplicación web gestor de proyectos el cual usa la arquitectura Model, View, Controller y usa la base de datos MySQL y maneja el CRUD para proyectos y tareas.

El proyecto está escrito sobre PHP y usa Composer como gestor de dependendias, además el diseño esta hecho con SASS así como Typescript para mandar peticiones por medio del *Fetch API*, *virtual DOM*, el proyecto contiene **Contenido Dinámico**, **Validación y Manejo de Formularios**, **Login y acceso a un panel de administración**, **Registro y autenticación de usuarios**, **Reestrablecer y Hashear Passwords**

Para ejecutar el projecto sigue los siguientes pasos:

1. Ejecuta el comando ```npm i``` para reconstruir los ```node_modules```
2. Utilizar el comando ```composer install``` para reconstruir la carpeta ```vendor```
3. Usa el comando ```gulp``` para transpilar los archivos ```.scss``` y ```.ts```

### Notas
- Los comandos deben ejecutarse dentro de la carpeta del proyecto
- Se debe tener instalado **node js** para ejecutar los comandos *npm*
- Ademas tener instalado **PHP** junto con **Composer** para usar los comandos *composer*
- Se puede ejecutar el proyecto con el servidor **Apache** o a traves del **Servidor local de PHP** con el comando ```php -S localhost:8000``` donde se encuentra el archivo ```ìndex.php```
