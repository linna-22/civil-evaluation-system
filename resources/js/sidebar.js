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
});