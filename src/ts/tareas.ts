(() => {

    const urlBase = location.origin;

    interface Tarea {
        id: string;
        nombre: string;
        estado: string;
        proyectosId: string;
    }

    interface Resultado {
        tipo: string;
        mensaje: string;
        tareas: Tarea[];
    }

    let tareas: Tarea[] = [];
    let tareasFiltradas: Tarea[] = [];

    obtenerTareas();

    const filtros = document.querySelectorAll("#filtros input[type='radio']");
    filtros.forEach(filtro => {
        filtro.addEventListener("input", filtrarTareas);
    });

    function filtrarTareas(e: Event) {

        const filtroSeleccionado = e.target as HTMLInputElement;

        if (filtroSeleccionado.value !== "") {
            tareasFiltradas = tareas.filter(tarea => tarea.estado === filtroSeleccionado.value);
        } else {
            tareasFiltradas = [];
        }

        mostrarTareas();
    }

    const nuevaTareaBoton = document.querySelector("#agregar-tarea");
    nuevaTareaBoton?.addEventListener("click", () => mostrarFormulario({
        id: "",
        nombre: "",
        estado: "",
        proyectosId: ""
    }));

    function mostrarFormulario(tarea: Tarea, editando = false) {

        const modal = document.createElement("DIV");
        modal.classList.add("modal");

        const formularioTarea = document.createElement("FORM") as HTMLFormElement;
        formularioTarea.classList.add("formulario", "nueva-tarea");
        //formularioTarea.method = "POST";

        const legendTitulo = document.createElement("LEGEND");
        legendTitulo.textContent = editando ? "Editar Tarea" : "Añade una nueva tarea";

        const divCampoTarea = document.createElement("DIV");
        divCampoTarea.classList.add("campo");

        const labelTarea = document.createElement("LABEL") as HTMLLabelElement;
        labelTarea.textContent = "Tarea";
        labelTarea.htmlFor = "tarea"

        const inputTarea = document.createElement("INPUT") as HTMLInputElement;
        inputTarea.type = "text";
        inputTarea.name = "nombre";
        inputTarea.id = "tarea";
        inputTarea.placeholder = editando ? "Edita la tarea" : "Añadir tarea al proyecto actual";
        inputTarea.value = tarea.nombre;

        const divOpciones = document.createElement("DIV");
        divOpciones.classList.add("opciones");

        const submitNuevaTarea = document.createElement("INPUT") as HTMLInputElement;
        submitNuevaTarea.classList.add("submit-nueva-tarea")
        submitNuevaTarea.type = "submit";
        submitNuevaTarea.value = editando ? "Guardar Cambios" : "Añadir Tarea";
        submitNuevaTarea.onclick = () => {

            const tareaValor = inputTarea.value.trim();

            if (!tareaValor) {
                mostrarAlerta("El nombre de la tarea es obligatorio", "error");
                return;
            }

            if (editando) {
                tarea.nombre = tareaValor;
                actualizarTarea(tarea);
            } else {
                agregarTarea(formularioTarea);
            }
        }

        const botonCerrarModal = document.createElement("BUTTON") as HTMLButtonElement;
        botonCerrarModal.classList.add("cerrar-modal");
        botonCerrarModal.type = "button";
        botonCerrarModal.textContent = "Cancelar";

        modal.appendChild(formularioTarea);
        formularioTarea.appendChild(legendTitulo);
        formularioTarea.appendChild(divCampoTarea);
        divCampoTarea.appendChild(labelTarea);
        divCampoTarea.appendChild(inputTarea);
        formularioTarea.appendChild(divOpciones);
        divOpciones.appendChild(submitNuevaTarea);
        divOpciones.appendChild(botonCerrarModal);

        setTimeout(() => {
            formularioTarea.classList.add("animar");
        }, 0);

        modal.addEventListener("click", (e) => {
            e.preventDefault();
            const elementoSeleccionado = e.target as HTMLElement;
            const clase = elementoSeleccionado.classList.value;

            if (clase === "modal" || clase === "cerrar-modal") {
                formularioTarea.classList.add("cerrar");
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }
        });

        const dashboard = document.querySelector(".dashboard");
        dashboard?.appendChild(modal);
    }

    function mostrarAlerta(mensaje: string, tipo: string, referencia = document.querySelector(".formulario legend")) {

        const alertaPrevia = document.querySelector(".alerta");

        if (alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement("DIV");
        alerta.classList.add("alerta", tipo);
        alerta.textContent = mensaje;
        referencia!.parentElement?.insertBefore(alerta, referencia!.nextElementSibling);

        setTimeout(() => {
            alerta.remove();
        }, 2000);
    }

    async function agregarTarea(formulario: HTMLFormElement) {

        const datos = new FormData(formulario);
        datos.append("proyectosId", obtenerProyecto());

        try {
            const url = `${urlBase}/api/tarea`;
            const respuesta = await fetch(url, {
                method: "POST",
                body: datos
            });
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje, resultado.tipo);

            if (resultado.tipo === "exito") {
                const modal = document.querySelector(".modal");
                setTimeout(() => {
                    modal?.remove();
                }, 1000);

                // seccion aplicando el Virtual DOM
                const tareaObjeto: Tarea = {
                    id: resultado.id,
                    nombre: datos.get("nombre") as string,
                    estado: "0",
                    proyectosId: resultado.proyectoId
                }

                tareas = [...tareas, tareaObjeto];
                mostrarTareas();
            }
        } catch (error) {
            console.error(error);
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return proyecto.id;
    }

    async function obtenerTareas() {
        try {
            const url = `${urlBase}/api/tareas?id=${obtenerProyecto()}`;
            const respuesta = await fetch(url);
            const resultado: Resultado = await respuesta.json()

            if (resultado.tipo === "error") {
                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector("#listado-tareas"));
                return;
            }

            tareas = resultado.tareas;
            mostrarTareas();

        } catch (error) {
            console.error(error);
        }
    }

    function mostrarTareas() {

        limpiarHTML();

        totalPendientes();
        totalCompletas();

        const arrayTareas = tareasFiltradas.length ? tareasFiltradas : tareas;

        const listadoTareas = document.querySelector("#listado-tareas");

        if (arrayTareas.length === 0) {
            const textoNoTareas = document.createElement("LI");
            textoNoTareas.textContent = "No hay tareas";
            textoNoTareas.classList.add("no-tareas");
            listadoTareas?.appendChild(textoNoTareas);
            return;
        }

        interface Estados {
            0: string;
            1: string;
        }

        const estados: Estados = {
            0: "pendiente",
            1: "completa"
        }

        arrayTareas.forEach(tarea => {

            const { id, nombre, estado } = tarea;
            const llaveEstado = estado as unknown as keyof Estados;

            const contenedorTarea = document.createElement("LI");
            contenedorTarea.dataset.tareaId = id;
            contenedorTarea.classList.add("tarea");

            const nombreTarea = document.createElement("P");
            nombreTarea.textContent = nombre;
            nombreTarea.onclick = () => mostrarFormulario({ ...tarea }, true);

            const divOpciones = document.createElement("DIV");
            divOpciones.classList.add("opciones");

            const botonEstadoTarea = document.createElement("BUTTON") as HTMLButtonElement;
            botonEstadoTarea.classList.add("estado-tarea", `${estados[llaveEstado]}`);
            botonEstadoTarea.textContent = estados[llaveEstado];
            botonEstadoTarea.dataset.estadoTarea = estado;
            botonEstadoTarea.type = "button";
            botonEstadoTarea.onclick = () => cambiarEstadoTarea({ ...tarea });

            const botonEliminarTarea = document.createElement("BUTTON") as HTMLButtonElement;
            botonEliminarTarea.classList.add("eliminar-tarea");
            botonEliminarTarea.dataset.idTarea = id;
            botonEliminarTarea.textContent = "Eliminar";
            botonEliminarTarea.type = "button";
            botonEliminarTarea.onclick = () => confirmarEliminarTarea({ ...tarea });

            divOpciones.appendChild(botonEstadoTarea);
            divOpciones.appendChild(botonEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(divOpciones);
            listadoTareas?.appendChild(contenedorTarea);
        });
    }

    function limpiarHTML() {
        const listadoTareas = document.querySelector("#listado-tareas");
        while (listadoTareas?.firstChild) {
            listadoTareas.firstChild.remove();
        }
    }

    function cambiarEstadoTarea(tarea: Tarea) {
        tarea.estado = tarea.estado === "1" ? "0" : "1";
        actualizarTarea(tarea);
    }

    async function actualizarTarea(tarea: Tarea) {

        const { id, nombre, estado } = tarea;

        const datosTarea = new FormData();
        datosTarea.append("id", id);
        datosTarea.append("nombre", nombre);
        datosTarea.append("estado", estado);
        datosTarea.append("proyectosId", obtenerProyecto());

        try {
            const url = `${urlBase}/api/tarea/actualizar`;
            const respuesta = await fetch(url, {
                method: "POST",
                body: datosTarea
            });
            const resultado = await respuesta.json();

            if (resultado.tipo === "exito") {

                const modal = document.querySelector(".modal");
                modal?.remove();

                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".contenedor-nueva-tarea"));

                tareas = tareas.map(tareaItem => {

                    if (tareaItem.id === id) {
                        tareaItem.estado = estado;
                        tareaItem.nombre = nombre;
                    }

                    return tareaItem;
                });

                mostrarTareas();

            }
        } catch (error) {
            console.error(error);
        }
    }

    function confirmarEliminarTarea(tarea: Tarea) {
        const respuesta = confirm("Esta seguro de eliminar esta tarea?");
        if (respuesta) {
            eliminarTarea(tarea);
        }
    }

    async function eliminarTarea(tarea: Tarea) {

        const { id, nombre, estado } = tarea;

        const datosTarea = new FormData();
        datosTarea.append("id", id);
        datosTarea.append("nombre", nombre);
        datosTarea.append("estado", estado);
        datosTarea.append("proyectosId", obtenerProyecto());

        try {
            const url = `${urlBase}/api/tarea/eliminar`;
            const respuesta = await fetch(url, {
                method: "POST",
                body: datosTarea
            });
            const resultado = await respuesta.json();

            if (resultado.exito) {
                mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector(".contenedor-nueva-tarea"));
                tareas = tareas.filter(tareaItem => tareaItem.id !== id);
                mostrarTareas();
            }
        } catch (error) {
            console.error(error);
        }
    }

    function totalPendientes() {
        const tareasPendientes = tareas.filter(tarea => tarea.estado === "0").length;
        const inputPendiente = document.querySelector("#pendientes") as HTMLInputElement;
        
        if (tareasPendientes === 0) {
            inputPendiente.disabled = true;
        } else {
            inputPendiente.disabled = false;
        }
    }

    function totalCompletas() {
        const tareasCompletas = tareas.filter(tarea => tarea.estado === "1").length;
        const inputCompletas = document.querySelector("#completadas") as HTMLInputElement;
        
        if (tareasCompletas === 0) {
            inputCompletas.disabled = true;
        } else {
            inputCompletas.disabled = false;
        }
    }
})();

