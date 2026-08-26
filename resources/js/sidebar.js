document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // Elements
    // =====================================================

    const sidebar =
        document.getElementById("sidebar");

    const toggle =
        document.getElementById("sidebarToggle");

    const overlay =
        document.getElementById("sidebarOverlay");
    const mobileMenuBtn =
        document.getElementById("mobileMenuBtn");


    if (!sidebar || !toggle) {
        return;
    }

    if (mobileMenuBtn) {

        mobileMenuBtn.addEventListener(
            "click",
            () => {
                openMobileSidebar();
            }
        );
    }
    // =====================================================
    // Desktop Sidebar Collapse
    // =====================================================
    toggle.addEventListener("click", () => {

        // =================================================
        // Mobile
        // =================================================

        if (window.innerWidth < 1024) {

            if (
                sidebar.classList.contains("translate-x-0")
            ) {

                closeMobileSidebar();

            } else {

                openMobileSidebar();

            }

            return;

        }


        // =================================================
        // Desktop
        // =================================================

        if (sidebar.classList.contains("collapsed")) {

            expandSidebar();

        } else {

            collapseSidebar();

        }

    });
    // =====================================================
    // Desktop Collapse
    // =====================================================
    function collapseSidebar() {
        sidebar.classList.add("collapsed");
        localStorage.setItem(
            "sidebar",
            "collapsed"
        );
    }
    // =====================================================
    // Desktop Expand
    // =====================================================
    function expandSidebar() {
        sidebar.classList.remove("collapsed");
        localStorage.setItem(
            "sidebar",
            "expanded"
        );
    }
    // =====================================================
    // Mobile Open
    // =====================================================
    function openMobileSidebar() {
        sidebar.classList.remove(
            "-translate-x-full"
        );
        sidebar.classList.add(
            "translate-x-0"
        );
        if (overlay) {
            overlay.classList.remove("hidden");
        }
    }
    // =====================================================
    // Mobile Close
    // =====================================================
    function closeMobileSidebar() {
        sidebar.classList.remove(
            "translate-x-0"
        );
        sidebar.classList.add(
            "-translate-x-full"
        );
        if (overlay) {
            overlay.classList.add("hidden");
        }
    }
    // =====================================================
    // Overlay Click
    // =====================================================
    if (overlay) {
        overlay.addEventListener(
            "click",
            closeMobileSidebar
        );
    }
    // =====================================================
    // Restore Desktop State
    // =====================================================
    if (window.innerWidth >= 1024) {
        if (
            localStorage.getItem("sidebar")
            === "collapsed"
        ) {
            collapseSidebar();
        }
    }
    // =====================================================
    // Handle Window Resize
    // =====================================================
    window.addEventListener(
        "resize",
        () => {
            if (window.innerWidth >= 1024) {
                // Close mobile state
                sidebar.classList.remove(
                    "-translate-x-full",
                    "translate-x-0"
                );
                if (overlay) {
                    overlay.classList.add(
                        "hidden"
                    );
                }
            } else {
                // Mobile starts hidden
                sidebar.classList.remove(
                    "collapsed"
                );
                sidebar.classList.add(
                    "-translate-x-full"
                );
            }
        }
    );
    // =====================================================
    // Sidebar Dropdown Menus
    // =====================================================

    const dropdownToggles = document.querySelectorAll(
        ".sidebar-dropdown-toggle"
    );

    dropdownToggles.forEach((button) => {

        button.addEventListener("click", () => {

            const submenu =
                button.nextElementSibling;

            const arrow =
                button.querySelector(
                    ".sidebar-dropdown-arrow"
                );

            if (!submenu) return;


            const isOpen =
                !submenu.classList.contains("hidden");


            // Close all other dropdowns
            document
                .querySelectorAll(".sidebar-submenu")
                .forEach((menu) => {

                    if (menu !== submenu) {

                        menu.classList.add("hidden");

                    }

                });


            // Reset other arrows
            document
                .querySelectorAll(
                    ".sidebar-dropdown-arrow"
                )
                .forEach((item) => {

                    if (item !== arrow) {

                        item.classList.remove(
                            "rotate-90"
                        );

                    }

                });


            // Toggle current dropdown
            if (isOpen) {

                submenu.classList.add("hidden");

                arrow?.classList.remove(
                    "rotate-90"
                );

                button.setAttribute(
                    "aria-expanded",
                    "false"
                );

            } else {

                submenu.classList.remove("hidden");

                arrow?.classList.add(
                    "rotate-90"
                );

                button.setAttribute(
                    "aria-expanded",
                    "true"
                );

            }

            // Re-render Lucide icons
            if (window.lucide) {
                lucide.createIcons();
            }

        });

    });
    // =====================================================
    // Close Mobile Sidebar After Clicking Menu
    // =====================================================

    document
        .querySelectorAll("#sidebar a")
        .forEach((link) => {

            link.addEventListener("click", () => {

                if (window.innerWidth < 1024) {

                    closeMobileSidebar();

                }

            });

        });

    // =====================================================
// Expand Sidebar When Clicking Icon
// =====================================================

const sidebarMenuItems = document.querySelectorAll(
    "#sidebar .sidebar-item, #sidebar .sidebar-dropdown-toggle"
);

sidebarMenuItems.forEach((item) => {

    item.addEventListener("click", (event) => {

        // Only desktop
        if (window.innerWidth < 1024) {
            return;
        }

        // Sidebar is collapsed
        if (sidebar.classList.contains("collapsed")) {

            // Prevent navigation for normal links
            if (item.classList.contains("sidebar-item")) {
                event.preventDefault();
            }

            // Expand sidebar
            expandSidebar();

            // If dropdown button
            if (
                item.classList.contains(
                    "sidebar-dropdown-toggle"
                )
            ) {

                const submenu =
                    item.nextElementSibling;

                const arrow =
                    item.querySelector(
                        ".sidebar-dropdown-arrow"
                    );

                if (submenu) {

                    submenu.classList.remove(
                        "hidden"
                    );

                }

                if (arrow) {

                    arrow.classList.add(
                        "rotate-90"
                    );

                }

                item.setAttribute(
                    "aria-expanded",
                    "true"
                );
            }

            // Re-render icons
            if (window.lucide) {
                lucide.createIcons();
            }

        }

    });

});
});