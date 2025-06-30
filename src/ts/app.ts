const sidebar = document.querySelector(".sidebar");

const mobileMenu = document.querySelector("#mobile-menu");
mobileMenu?.addEventListener("click", () => sidebar?.classList.add("mostrar"));

const mobileCerrar = document.querySelector("#mobile-cerrar");
mobileCerrar?.addEventListener("click", () => {
    sidebar?.classList.add("ocultar");

    setTimeout(() => {
        sidebar?.classList.remove("mostrar");
        sidebar?.classList.remove("ocultar");
    }, 1000);
});


window.addEventListener("resize", () => {

    const anchoPantalla = document.body.clientWidth;
    
    if (anchoPantalla > 768) {
        sidebar?.classList.remove("mostrar");
    }
});