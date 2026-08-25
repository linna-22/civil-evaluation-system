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
    // Validate Current User
    // =====================================================

    async function validateCurrentUser() {

        const currentUser =
            getCurrentUser();

        if (!currentUser) {
            return false;
        }

        // -------------------------------------------------
        // Get current attendance data
        // -------------------------------------------------

        const data =
            window.attendanceForm
                ? window.attendanceForm.collect(
                    currentUser.user_id
                )
                : null;

        if (!data) {
            return false;
        }

        // -------------------------------------------------
        // Perfect Attendance
        // -------------------------------------------------

        if (data.perfectAttendance) {
            return true;
        }

        // -------------------------------------------------
        // Attendance Form
        // -------------------------------------------------

        const approved =
            Number(data.approved_leave_days || 0);

        const unapproved =
            Number(data.unapproved_leave_days || 0);

        const late =
            Number(data.late_hours || 0);

        const leaveEarly =
            Number(data.leave_early_hours || 0);

        // -------------------------------------------------
        // At least one attendance value
        // -------------------------------------------------

        if (
            approved === 0 &&
            unapproved === 0 &&
            late === 0 &&
            leaveEarly === 0
        ) {

            await Swal.fire({

                icon: "warning",

                title: "មិនអាចបន្តបានទេ",

                text:
                    "សូមបញ្ចូលព័ត៌មានវត្តមានយ៉ាងហោចណាស់មួយជួរ។",

                confirmButtonText: "យល់ព្រម",

                confirmButtonColor: "#2563eb"

            });

            return false;
        }

        return true;
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