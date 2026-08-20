import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {

    const previousBtn =
        document.getElementById("previousUserBtn");

    const nextBtn =
        document.getElementById("nextUserBtn");

    if (!previousBtn || !nextBtn) {
        return;
    }


    // =====================================================
    // Update Navigation
    // =====================================================

    function updateNavigation(currentIndex, totalUsers) {

        // -------------------------------------------------
        // Previous
        // -------------------------------------------------

        const isFirstUser =
            currentIndex === 0;

        previousBtn.disabled =
            isFirstUser;

        previousBtn.classList.toggle(
            "opacity-50",
            isFirstUser
        );

        previousBtn.classList.toggle(
            "cursor-not-allowed",
            isFirstUser
        );


        // -------------------------------------------------
        // Next
        // -------------------------------------------------

        const isLastUser =
            currentIndex === totalUsers - 1;

        if (isLastUser) {

            nextBtn.innerHTML = `
                ពិនិត្យលទ្ធផល

                <i
                    data-lucide="eye"
                    class="w-4 h-4">
                </i>
            `;

        } else {

            nextBtn.innerHTML = `
                បន្ទាប់

                <i
                    data-lucide="arrow-right"
                    class="w-4 h-4">
                </i>
            `;

        }

        refreshIcons();

    }


    // Make available to create.js
    window.workPerformanceNavigation = {
        update: updateNavigation
    };

});