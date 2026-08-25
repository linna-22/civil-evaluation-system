document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // Elements
    // =====================================================

    const toggle = document.getElementById("perfectAttendance");
    const form = document.getElementById("attendanceForm");
    const card = document.getElementById("attendanceCard");
    const title = document.getElementById("attendanceTitle");
    const description = document.getElementById("attendanceDescription");
    const approvedLeave = document.querySelector('[name="approved_leave_days"]');
    const unapprovedLeave = document.querySelector('[name="unapproved_leave_days"]');
    const lateHours = document.querySelector('[name="late_hours"]');
    const leaveEarlyHours = document.querySelector('[name="leave_early_hours"]');
    // Session Storage
    const storageKey = "attendanceEvaluationData";
    // Current User
    let currentUserId = null;
    // Get Stored Data
    function getStoredData() {
        try {
            return JSON.parse(sessionStorage.getItem(storageKey)) || {};
        } catch (error) {
            console.error("Unable to read attendance data:", error);
            return {};
        }
    }
    // Save Stored Data
    function saveStoredData(data) {
        sessionStorage.setItem(storageKey, JSON.stringify(data));
    }
    // Default Data
    function getDefaultData() {
        return {
            perfectAttendance: true,
            approved_leave_days: 0,
            unapproved_leave_days: 0,
            late_hours: 0,
            leave_early_hours: 0
        };
    }
    // Clear Attendance Inputs
    function clearAttendanceInputs() {
        if (approvedLeave) {
            approvedLeave.value = 0;
        }
        if (unapprovedLeave) {
            unapprovedLeave.value = 0;
        }
        if (lateHours) {
            lateHours.value = 0;
        }
        if (leaveEarlyHours) {
            leaveEarlyHours.value = 0;
        }
    }
    // Save Perfect Attendance
    function savePerfectAttendance() {
        if (!currentUserId) {
            return;
        }
        const allData = getStoredData();
        allData[currentUserId] = {
            perfectAttendance: true,
            approved_leave_days: 0,
            unapproved_leave_days: 0,
            late_hours: 0,
            leave_early_hours: 0
        };
        saveStoredData(allData);
    }
    // Update Attendance UI
    function updateAttendanceUI() {
        if (!toggle || !form || !card) {
            return;
        }
        if (toggle.checked) {
            // Perfect Attendance
            form.classList.add("hidden");
            card.classList.remove(
                "bg-amber-50",
                "border-amber-300"
            );
            card.classList.add(
                "bg-green-50",
                "border-green-200"
            );
            if (title) {
                title.classList.remove(
                    "text-amber-700"
                );
                title.classList.add(
                    "text-green-700"
                );
                title.textContent =
                    "មិនមានអវត្តមាន";
            }
            if (description) {
                description.classList.remove(
                    "text-amber-600"
                );
                description.classList.add(
                    "text-green-600"
                );
                description.textContent =
                    "ខ្ញុំមិនមានការឈប់សម្រាកទេក្នុងខែវាយតម្លៃនេះ។";
            }
        } else {
            // Has Attendance Issues
            form.classList.remove("hidden");
            card.classList.remove(
                "bg-green-50",
                "border-green-200"
            );
            card.classList.add(
                "bg-amber-50",
                "border-amber-300"
            );
            if (title) {
                title.classList.remove(
                    "text-green-700"
                );
                title.classList.add(
                    "text-amber-700"
                );
                title.textContent =
                    "សូមបំពេញព័ត៌មានវត្តមាន";
            }
            if (description) {
                description.classList.remove(
                    "text-green-600"
                );
                description.classList.add(
                    "text-amber-600"
                );
                description.textContent =
                    "សូមបំពេញព័ត៌មានការឈប់សម្រាកខាងក្រោម។";
            }
        }
    }
    // Load User Data
    function load(userId) {
        if (!userId) {
            return;
        }
        // Remember Current User
        currentUserId = userId;
        // Get Stored Data
        const allData = getStoredData();
        const data = allData[userId] || getDefaultData();
        // Perfect Attendance
        if (toggle) {
            toggle.checked = data.perfectAttendance ?? true;
        }
        // Approved Leave
        if (approvedLeave) {
            approvedLeave.value = data.approved_leave_days ?? 0;
        }
        // Unapproved Leave
        if (unapprovedLeave) {
            unapprovedLeave.value = data.unapproved_leave_days ?? 0;
        }
        // Late Hours
        if (lateHours) {
            lateHours.value = data.late_hours ?? 0;
        }
        // Leave Early
        if (leaveEarlyHours) {
            leaveEarlyHours.value = data.leave_early_hours ?? 0;
        }
        // -------------------------------------------------
        // If Perfect Attendance
        // Always Keep Inputs Clear
        // -------------------------------------------------

        if (toggle && toggle.checked) {
            clearAttendanceInputs();
        }
        // Update UI
        updateAttendanceUI();
    }
    // Collect Current User Data
    function collect(userId) {
        if (!userId) {
            return null;
        }
        // Perfect Attendance
        if (toggle && toggle.checked) {
            const data = {
                perfectAttendance: true,
                approved_leave_days: 0,
                unapproved_leave_days: 0,
                late_hours: 0,
                leave_early_hours: 0
            };
            const allData = getStoredData();
            allData[userId] = data;
            saveStoredData(allData);
            return data;
        }
        // Attendance With Issues
        const data = {
            perfectAttendance: false,
            approved_leave_days:
                Number(approvedLeave?.value || 0),
            unapproved_leave_days:
                Number(unapprovedLeave?.value || 0),
            late_hours:
                Number(lateHours?.value || 0),
            leave_early_hours:
                Number(leaveEarlyHours?.value || 0)
        };
        const allData = getStoredData();
        allData[userId] = data;
        saveStoredData(allData);
        return data;
    }
    // Clear Form
    function reset() {
        if (toggle) {
            toggle.checked = true;
        }
        clearAttendanceInputs();
        updateAttendanceUI();
    }
    // Toggle Event
    if (toggle) {
        toggle.addEventListener(
            "change",
            () => {
                // Perfect Attendance
                if (toggle.checked) {
                    // Clear old attendance values
                    clearAttendanceInputs();
                    // Save clean perfect attendance
                    savePerfectAttendance();
                }
                // Update UI
                updateAttendanceUI();
            }
        );
    }
    // Public API
    window.attendanceForm = {
        load: load,
        collect: collect,
        reset: reset,
        updateUI: updateAttendanceUI
    };
    // Initial UI
    updateAttendanceUI();
});