document.addEventListener("DOMContentLoaded", () => {

    const sidebar = document.getElementById("sidebar");
    const toggle = document.getElementById("sidebarToggle");

    if (!sidebar || !toggle) return;

    // Restore previous state
    // if (localStorage.getItem("sidebar") === "collapsed") {
    //     collapseSidebar();
    // }

    toggle.addEventListener("click", () => {

        if (sidebar.classList.contains("collapsed")) {
            expandSidebar();
        } else {
            collapseSidebar();
        }

    });

    function collapseSidebar() {

        sidebar.classList.add("collapsed");

        localStorage.setItem("sidebar", "collapsed");

    }

    function expandSidebar() {

        sidebar.classList.remove("collapsed");

        localStorage.setItem("sidebar", "expanded");

    }

});