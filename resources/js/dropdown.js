document.addEventListener("DOMContentLoaded", () => {

    const toggle = document.getElementById("userDropdownToggle");
    const menu = document.getElementById("userDropdownMenu");

    if (!toggle || !menu) return;

    toggle.addEventListener("click", (e) => {

        e.stopPropagation();

        menu.classList.toggle("hidden");

        menu.classList.toggle("opacity-0");
        menu.classList.toggle("scale-95");

    });

    // Close when clicking outside
    document.addEventListener("click", () => {

        menu.classList.add("hidden");
        menu.classList.add("opacity-0");
        menu.classList.add("scale-95");

    });

    // Close with ESC
    document.addEventListener("keydown", (e) => {

        if (e.key === "Escape") {

            menu.classList.add("hidden");
            menu.classList.add("opacity-0");
            menu.classList.add("scale-95");

        }

    });

});