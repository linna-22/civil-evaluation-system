document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // Users
    // =====================================================

    const users =
        window.workPerformanceUsers || [];

    if (!users.length) {
        return;
    }


    // =====================================================
    // State
    // =====================================================

    let currentIndex = 0;

    const storedData =
        sessionStorage.getItem(
            "workPerformanceEvaluationData"
        );

    const savedData =
        storedData
            ? JSON.parse(storedData)
            : null;

    const answers =
        savedData?.answers || {};


    // =====================================================
    // Elements
    // =====================================================

    const currentUserName =
        document.getElementById(
            "currentUserName"
        );

    const currentUserId =
        document.getElementById(
            "currentUserId"
        );

    const previousUserBtn =
        document.getElementById(
            "previousUserBtn"
        );

    const nextUserBtn =
        document.getElementById(
            "nextUserBtn"
        );


    if (
        !currentUserName ||
        !previousUserBtn ||
        !nextUserBtn
    ) {
        return;
    }


    // =====================================================
    // Create Empty User Data
    // =====================================================

    function createEmptyUserData() {

        return {
            performances: []
        };

    }


    // =====================================================
    // Get Current User
    // =====================================================

    function getCurrentUser() {

        return users[currentIndex];

    }


    // =====================================================
    // Ensure User Data Exists
    // =====================================================

    function ensureUserData(userId) {

        if (!answers[userId]) {

            answers[userId] =
                createEmptyUserData();

        }

    }


    // =====================================================
    // Save Current User
    // =====================================================

    function saveCurrentUser() {

        const user =
            getCurrentUser();

        if (!user) {
            return;
        }

        ensureUserData(user.user_id);


        /*
         * Table.js will later provide the table data.
         *
         * For now we keep the existing data structure.
         */

        if (
            window.workPerformanceTable &&
            typeof window.workPerformanceTable.getData ===
                "function"
        ) {

            answers[user.user_id] = {

                performances:
                    window.workPerformanceTable.getData()

            };

        }

    }


    // =====================================================
    // Load Current User
    // =====================================================

    function loadCurrentUser() {

        const user =
            getCurrentUser();

        if (!user) {
            return;
        }

        ensureUserData(user.user_id);


        // -------------------------------------------------
        // User Information
        // -------------------------------------------------

        currentUserName.textContent =
            user.name_kh || "";


        if (currentUserId) {

            currentUserId.textContent =
                user.id_code || "-";

        }


        // -------------------------------------------------
        // Load Performance Data
        // -------------------------------------------------

        const userData =
            answers[user.user_id];


        if (
            window.workPerformanceTable &&
            typeof window.workPerformanceTable.loadData ===
                "function"
        ) {

            window.workPerformanceTable.loadData(
                userData?.performances || []
            );

        }


        // -------------------------------------------------
        // Update Progress
        // -------------------------------------------------

        if (
            window.workPerformanceProgress &&
            typeof window.workPerformanceProgress.render ===
                "function"
        ) {

            window.workPerformanceProgress.render(
                currentIndex
            );

        }


        // -------------------------------------------------
        // Update Navigation
        // -------------------------------------------------

        if (
            window.workPerformanceNavigation &&
            typeof window.workPerformanceNavigation.update ===
                "function"
        ) {

            window.workPerformanceNavigation.update(
                currentIndex,
                users.length
            );

        }

    }


    // =====================================================
    // Previous
    // =====================================================

    previousUserBtn.addEventListener(
        "click",
        () => {

            if (currentIndex === 0) {
                return;
            }


            // Save current user

            saveCurrentUser();


            // Move backward

            currentIndex--;


            // Load previous user

            loadCurrentUser();

        }
    );


    // =====================================================
    // Next
    // =====================================================

    nextUserBtn.addEventListener(
        "click",
        () => {

            // Save current user

            saveCurrentUser();


            // -------------------------------------------------
            // Next User
            // -------------------------------------------------

            if (
                currentIndex <
                users.length - 1
            ) {

                currentIndex++;

                loadCurrentUser();

                return;

            }


            // -------------------------------------------------
            // Last User → Preview
            // -------------------------------------------------

            const evaluationData = {

                users: users,

                answers: answers

            };


            sessionStorage.setItem(
                "workPerformanceEvaluationData",
                JSON.stringify(
                    evaluationData
                )
            );


            window.location.href =
                "/evaluations/work-performance/preview";

        }
    );


    // =====================================================
    // Start
    // =====================================================

    loadCurrentUser();

});