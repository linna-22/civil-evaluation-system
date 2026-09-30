import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("perfectAttendance");
    const form = document.getElementById("attendanceForm");
    const card = document.getElementById("attendanceCard");
    const title = document.getElementById("attendanceTitle");
    const description = document.getElementById("attendanceDescription");

    const approvedLeave = document.getElementById("approvedLeaveDays");
    const unapprovedLeave = document.getElementById("unapprovedLeaveDays");
    const lateHours = document.getElementById("lateHours");
    const leaveEarlyHours = document.getElementById("leaveEarlyHours");

    const attendancePercent = document.getElementById("attendancePercent");
    const attendanceScore = document.getElementById("attendanceScore");

    if (!toggle || !form || !card) {
        return;
    }

    function calculatePercent() {
        if (toggle.checked) {
            return 100;
        }

        const approvedHours =
            Number(approvedLeave?.value || 0) * 8 * 0.5;
        const unapprovedHours =
            Number(unapprovedLeave?.value || 0) * 8;
        const late = Number(lateHours?.value || 0);
        const leaveEarly = Number(leaveEarlyHours?.value || 0);

        const deductionHours =
            approvedHours + unapprovedHours + late + leaveEarly;

        const deductionPercent = deductionHours / 1.76;

        return Math.max(
            0,
            Number((100 - deductionPercent).toFixed(2))
        );
    }

    function calculateScore(percent) {
        if (percent < 80) return 0;
        if (percent < 90) return 5;
        if (percent < 95) return 10;
        if (percent < 100) return 15;
        return 20;
    }

    function updateCalculatedResult() {
        const percent = calculatePercent();
        const score = calculateScore(percent);

        if (attendancePercent) {
            attendancePercent.textContent = `${percent}%`;
        }

        if (attendanceScore) {
            attendanceScore.textContent = `${score}/20`;
        }
    }

    function clearDeductionInputs() {
        [approvedLeave, unapprovedLeave, lateHours, leaveEarlyHours].forEach(
            (input) => {
                if (input) input.value = 0;
            }
        );
    }

    function updateUI() {
        if (toggle.checked) {
            form.classList.add("hidden");

            card.classList.remove("bg-amber-50", "border-amber-300");
            card.classList.add("bg-green-50", "border-green-200");

            if (title) {
                title.classList.remove("text-amber-700");
                title.classList.add("text-green-700");
                title.textContent = "មិនមានអវត្តមាន";
            }

            if (description) {
                description.classList.remove("text-amber-600");
                description.classList.add("text-green-600");
                description.textContent =
                    "ខ្ញុំមិនមានការឈប់សម្រាកទេក្នុងខែវាយតម្លៃនេះ។";
            }

            clearDeductionInputs();
        } else {
            form.classList.remove("hidden");

            card.classList.remove("bg-green-50", "border-green-200");
            card.classList.add("bg-amber-50", "border-amber-300");

            if (title) {
                title.classList.remove("text-green-700");
                title.classList.add("text-amber-700");
                title.textContent = "សូមបំពេញព័ត៌មានវត្តមាន";
            }

            if (description) {
                description.classList.remove("text-green-600");
                description.classList.add("text-amber-600");
                description.textContent =
                    "សូមបំពេញព័ត៌មានការឈប់សម្រាកខាងក្រោម។";
            }
        }

        updateCalculatedResult();
        refreshIcons();
    }

    toggle.addEventListener("change", updateUI);

    [approvedLeave, unapprovedLeave, lateHours, leaveEarlyHours].forEach(
        (input) => {
            input?.addEventListener("input", () => {
                if (toggle.checked) {
                    toggle.checked = false;
                    updateUI();
                } else {
                    updateCalculatedResult();
                }
            });
        }
    );

    updateUI();
});
