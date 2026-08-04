export function loadPreview() {
    console.log("Loading preview...");

    loadEmployeeInfo();

    loadWorkPerformance();

    loadAttendance();

    loadBehavior();

}

/**
 * ============================================
 * Employee Information
 * ============================================
 */
// function loadEmployeeInfo() {

//     document.getElementById("previewEmployeeName").textContent =
//         document.getElementById("employeeName").value;

//     document.getElementById("previewGender").textContent =
//         document.getElementById("employeeGender").value;

//     document.getElementById("previewPosition").textContent =
//         document.getElementById("employeePosition").value;

//     document.getElementById("previewOrganization").textContent =
//         document.getElementById("employeeOrganization").value;

//     document.getElementById("previewDepartment").textContent =
//         document.getElementById("employeeDepartment").value;

//     document.getElementById("previewEvaluationMonth").textContent =
//         document.getElementById("evaluationMonthText").value;

// }
function loadEmployeeInfo() {

    const form = document.getElementById("evaluationForm");

    document.getElementById("previewEmployeeName").textContent =
        form.dataset.name;

    document.getElementById("previewGender").textContent =
        form.dataset.gender === "male"
            ? "ប្រុស"
            : "ស្រី";

    document.getElementById("previewPosition").textContent =
        form.dataset.position;

    document.getElementById("previewOrganization").textContent =
        form.dataset.organization;

    document.getElementById("previewDepartment").textContent =
        form.dataset.department;

    document.getElementById("previewEvaluationMonth").textContent =
        form.dataset.month;

}

/**
 * ============================================
 * Work Performance
 * ============================================
 */
function loadWorkPerformance() {

    const tbody = document.getElementById(
        "previewPerformanceTable"
    );

    tbody.innerHTML = "";

    let totalScore = 0;

    document.querySelectorAll(
        "#performanceTableBody tr"
    ).forEach((row, index) => {

        const activity = row.querySelector(
            '[name*="[activity]"]'
        )?.value ?? "";

        const indicator = row.querySelector(
            '[name*="[indicator]"]'
        )?.value ?? "";

        const percent = row.querySelector(
            '[name*="[achievement_percent]"]'
        )?.value ?? 0;

        const score = row.querySelector(
            'input[name*="[score]"]'
        )?.value ?? 0;

        totalScore += Number(score);

        tbody.innerHTML += `
            <tr>

                <td class="px-6 py-4">

                    ${index + 1}

                </td>

                <td class="px-6 py-4">

                    ${activity}

                </td>

                <td class="px-6 py-4">

                    ${indicator}

                </td>

                <td class="px-6 py-4 text-center">

                    ${percent}%

                </td>

                <td class="px-6 py-4 text-center font-semibold">

                    ${score}

                </td>

            </tr>
        `;

    });

    const workScore =
        calculateWorkPerformanceScore(
            totalScore
        );

    document.getElementById(
        "previewWorkPerformanceScore"
    ).textContent =
        `${workScore} / 60 ពិន្ទុ`;

}

/**
 * ============================================
 * Attendance
 * ============================================
 */
function loadAttendance() {

    const approved =
        Number(document.querySelector(
            '[name="approved_leave_days"]'
        ).value);

    const unapproved =
        Number(document.querySelector(
            '[name="unapproved_leave_days"]'
        ).value);

    const late =
        Number(document.querySelector(
            '[name="late_hours"]'
        ).value);

    const early =
        Number(document.querySelector(
            '[name="leave_early_hours"]'
        ).value);

    document.getElementById(
        "previewApprovedLeave"
    ).textContent = approved;

    document.getElementById(
        "previewUnapprovedLeave"
    ).textContent = unapproved;

    document.getElementById(
        "previewLateHours"
    ).textContent = late;

    document.getElementById(
        "previewLeaveEarlyHours"
    ).textContent = early;

    const attendanceScore =
        calculateAttendanceScore(
            approved,
            unapproved,
            late,
            early
        );
    const perfectAttendance =
        approved === 0 &&
        unapproved === 0 &&
        late === 0 &&
        early === 0;

    const perfectMessage = document.getElementById("perfectAttendanceMessage");
    const attendanceDetails = document.getElementById("attendanceDetails");

    if (perfectAttendance) {

        perfectMessage.classList.remove("hidden");
        attendanceDetails.classList.add("hidden");

    } else {

        perfectMessage.classList.add("hidden");
        attendanceDetails.classList.remove("hidden");

    }

    document.getElementById(
        "previewAttendanceScore"
    ).textContent =
        `${attendanceScore} / 20 ពិន្ទុ`;

}

/**
 * ============================================
 * Behavior
 * ============================================
 */
function loadBehavior() {

    const discipline =
        radioScore([
            "discipline",
            "responsibility",
            "professional_ethics"
        ]);

    const professional =
        radioScore([
            "work_performance",
            "self_development",
            "initiative_creativity"
        ]);

    const leadership =
        radioScore([
            "teamwork",
            "interpersonal_skill",
            "work_under_pressure",
            "leadership"
        ]);

    const total =
        discipline +
        professional +
        leadership;

    document.getElementById(
        "previewDisciplineScore"
    ).textContent =
        `${discipline} / 6`;

    document.getElementById(
        "previewProfessionalScore"
    ).textContent =
        `${professional} / 6`;

    document.getElementById(
        "previewLeadershipScore"
    ).textContent =
        `${leadership} / 8`;

    document.getElementById(
        "previewBehaviorScore"
    ).textContent =
        `${total} / 20 ពិន្ទុ`;

}

/**
 * ============================================
 * Helpers
 * ============================================
 */

function radioScore(fields) {

    let score = 0;

    fields.forEach(field => {

        const checked = document.querySelector(
            `input[name="${field}"]:checked`
        );

        score += Number(
            checked?.value ?? 0
        );

    });

    return score;

}

function calculateWorkPerformanceScore(totalScore) {

    if (totalScore <= 60) return 0;

    if (totalScore <= 70) return 15;

    if (totalScore <= 80) return 30;

    if (totalScore <= 90) return 45;

    return 60;

}

function calculateAttendanceScore(
    approvedDays,
    unapprovedDays,
    lateHours,
    leaveEarlyHours
) {

    // Perfect attendance
    if (
        approvedDays === 0 &&
        unapprovedDays === 0 &&
        lateHours === 0 &&
        leaveEarlyHours === 0
    ) {
        return 20;
    }

    // Approved leave deducts only 50%
    const approvedHours = approvedDays * 8 * 0.5;

    // Unapproved leave deducts 100%
    const unapprovedHours = unapprovedDays * 8;

    // Total deduction hours
    const deductionHours =
        approvedHours +
        unapprovedHours +
        lateHours +
        leaveEarlyHours;

    // 1% = 1.76 hours
    const attendancePercent =
        Math.max(
            0,
            100 - (deductionHours / 1.76)
        );

    // Convert attendance percent to score out of 20
    return Number(
        ((attendancePercent * 20) / 100).toFixed(2)
    );

}