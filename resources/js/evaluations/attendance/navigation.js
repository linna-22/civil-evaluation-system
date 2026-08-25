import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // Users
    // =====================================================

    const users =
        window.attendanceUsers || [];

    if (!users.length) {
        return;
    }


    // =====================================================
    // Elements
    // =====================================================

    const previousUserBtn =
        document.getElementById("previousUserBtn");

    const nextUserBtn =
        document.getElementById("nextUserBtn");


    if (!previousUserBtn || !nextUserBtn) {
        return;
    }


    // =====================================================
    // Current User Index
    // =====================================================

    let currentUserIndex = 0;


    // =====================================================
    // Get Current User
    // =====================================================

    function getCurrentUser() {

        return users[currentUserIndex];

    }


    // =====================================================
    // Render Current User
    // =====================================================

    function renderCurrentUser() {

        const currentUser =
            getCurrentUser();


        if (!currentUser) {
            return;
        }


        // -------------------------------------------------
        // Load User Attendance Data
        // -------------------------------------------------

        if (window.attendanceForm) {

            window.attendanceForm.load(
                currentUser.user_id
            );

        }


        // -------------------------------------------------
        // Update Progress Bar
        // -------------------------------------------------

        if (window.attendanceProgress) {

            window.attendanceProgress.render(
                currentUserIndex
            );

        }


        // -------------------------------------------------
        // Update Navigation
        // -------------------------------------------------

        updateNavigation();

    }


    // =====================================================
    // Update Navigation Buttons
    // =====================================================

    function updateNavigation() {

        // -------------------------------------------------
        // Previous Button
        // -------------------------------------------------

        const isFirstUser =
            currentUserIndex === 0;

        previousUserBtn.disabled =
            isFirstUser;

        previousUserBtn.classList.toggle(
            "opacity-50",
            isFirstUser
        );

        previousUserBtn.classList.toggle(
            "cursor-not-allowed",
            isFirstUser
        );


        // -------------------------------------------------
        // Next / Preview Button
        // -------------------------------------------------

        const isLastUser =
            currentUserIndex === users.length - 1;


        if (isLastUser) {

            nextUserBtn.innerHTML = `
                ពិនិត្យឡើងវិញ

                <i
                    data-lucide="eye"
                    class="w-4 h-4">
                </i>
            `;

        } else {

            nextUserBtn.innerHTML = `
                បន្ទាប់

                <i
                    data-lucide="arrow-right"
                    class="w-4 h-4">
                </i>
            `;

        }


        // -------------------------------------------------
        // Refresh Lucide Icons
        // -------------------------------------------------

        refreshIcons();

    }


    // =====================================================
    // Previous User
    // =====================================================

    previousUserBtn.addEventListener(
        "click",
        () => {

            // -------------------------------------------------
            // Cannot go before first user
            // -------------------------------------------------

            if (currentUserIndex <= 0) {
                return;
            }


            // -------------------------------------------------
            // Save Current User Data
            // -------------------------------------------------

            const currentUser =
                getCurrentUser();


            if (window.attendanceForm) {

                window.attendanceForm.collect(
                    currentUser.user_id
                );

            }


            // -------------------------------------------------
            // Move Previous
            // -------------------------------------------------

            currentUserIndex--;


            // -------------------------------------------------
            // Render Previous User
            // -------------------------------------------------

            renderCurrentUser();

        }
    );


    // =====================================================
    // Next User
    // =====================================================

    nextUserBtn.addEventListener(
        "click",
        () => {
            // Get Current User
            const currentUser = getCurrentUser();
            // Save Current User Data
            if (window.attendanceForm) {
                window.attendanceForm.collect(
                    currentUser.user_id
                );
            }
            // Move Next
            if (currentUserIndex < users.length - 1) {
                currentUserIndex++;
                renderCurrentUser();
                return;
            }


            // =====================================================
            // Last User → Preview
            // =====================================================

            const officeId =
                window.attendanceOfficeId;

            const departmentId =
                window.attendanceDepartmentId;

            if (officeId) {

                window.location.href =
                    `/evaluations/attendance/preview?office=${officeId}`;

            } else if (departmentId) {

                window.location.href =
                    `/evaluations/attendance/preview?department=${departmentId}`;

            }

        }
    );


    // =====================================================
    // Public API
    // =====================================================

    window.attendanceNavigation = {

        getCurrentIndex: () =>
            currentUserIndex,

        getCurrentUser: () =>
            getCurrentUser(),

        getUsers: () =>
            users,

        goTo: (index) => {

            if (
                index < 0 ||
                index >= users.length
            ) {
                return;
            }

            currentUserIndex =
                index;

            renderCurrentUser();

        }

    };


    // =====================================================
    // Initial Render
    // =====================================================

    renderCurrentUser();

});