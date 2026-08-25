import Swal from "sweetalert2";
import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // Elements
    // =====================================================

    const backBtn =
        document.getElementById("backToEvaluationBtnBottom");

    const submitBtn =
        document.getElementById("submitAttendanceBtn");

    const tbody =
        document.getElementById("attendancePreviewTable");


    if (!backBtn || !submitBtn || !tbody) {
        return;
    }


    // =====================================================
    // Users
    // =====================================================

    const users =
        window.attendanceUsers || [];


    if (!users.length) {
        return;
    }


    // =====================================================
    // Storage
    // =====================================================

    const storageKey =
        "attendanceEvaluationData";


    // =====================================================
    // Get Attendance Data
    // =====================================================

    function getAttendanceData() {

        try {

            return JSON.parse(
                sessionStorage.getItem(storageKey)
            ) || {};

        } catch (error) {

            console.error(
                "Unable to read attendance data:",
                error
            );

            return {};

        }

    }


    // =====================================================
    // Calculate Attendance Percent
    // =====================================================

    function calculateAttendancePercent(data) {

        const approvedHours =
            Number(data.approved_leave_days || 0)
            * 8
            * 0.5;


        const unapprovedHours =
            Number(data.unapproved_leave_days || 0)
            * 8;


        const lateHours =
            Number(data.late_hours || 0);


        const leaveEarlyHours =
            Number(data.leave_early_hours || 0);


        const deductionHours =
            approvedHours
            + unapprovedHours
            + lateHours
            + leaveEarlyHours;


        const deductionPercent =
            deductionHours / 1.76;


        return Math.max(
            0,
            Math.round(
                (100 - deductionPercent) * 100
            ) / 100
        );

    }


    // =====================================================
    // Calculate Attendance Score
    // =====================================================

    function calculateAttendanceScore(percent) {

        if (percent < 80) {
            return 0;
        }

        if (percent >= 100) {
            return 20;
        }

        if (percent >= 95) {
            return 15;
        }

        if (percent >= 90) {
            return 10;
        }

        return 5;

    }


    // =====================================================
    // Get Score Badge
    // =====================================================

    function getScoreClass(score) {

        if (score === 20) {
            return "bg-green-50 text-green-700";
        }

        if (score >= 15) {
            return "bg-blue-50 text-blue-700";
        }

        if (score >= 10) {
            return "bg-yellow-50 text-yellow-700";
        }

        if (score >= 5) {
            return "bg-orange-50 text-orange-700";
        }

        return "bg-red-50 text-red-700";

    }


    // =====================================================
    // Render Preview
    // =====================================================

    function renderPreview() {

        const attendanceData =
            getAttendanceData();


        tbody.innerHTML = "";


        users.forEach(
            (user, index) => {

                const data =
                    attendanceData[user.user_id]
                    || {
                        perfectAttendance: true,
                        approved_leave_days: 0,
                        unapproved_leave_days: 0,
                        late_hours: 0,
                        leave_early_hours: 0
                    };


                // -------------------------------------------------
                // Calculate Attendance
                // -------------------------------------------------

                const attendancePercent =
                    data.perfectAttendance
                        ? 100
                        : calculateAttendancePercent(data);


                const attendanceScore =
                    data.perfectAttendance
                        ? 20
                        : calculateAttendanceScore(
                            attendancePercent
                        );


                // -------------------------------------------------
                // Create Row
                // -------------------------------------------------

                const row =
                    document.createElement("tr");


                row.className =
                    "hover:bg-gray-50 transition";


                row.innerHTML = `

                    <td class="px-5 py-4 text-gray-500">
                        ${index + 1}
                    </td>

                    <td class="px-5 py-4">

                        <p class="font-medium text-gray-800">
                            ${user.name_kh || "-"}
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            ${user.id_code || "-"}
                        </p>

                    </td>

                    <td class="px-5 py-4 text-center text-gray-600">
                        ${data.approved_leave_days || 0}
                    </td>

                    <td class="px-5 py-4 text-center text-gray-600">
                        ${data.unapproved_leave_days || 0}
                    </td>

                    <td class="px-5 py-4 text-center text-gray-600">
                        ${data.late_hours || 0}
                    </td>

                    <td class="px-5 py-4 text-center text-gray-600">
                        ${data.leave_early_hours || 0}
                    </td>

                    <td class="px-5 py-4 text-center">

                        <span class="
                            inline-flex
                            items-center
                            px-3
                            py-1
                            rounded-full
                            bg-blue-50
                            text-blue-700
                            font-semibold
                        ">

                            ${attendancePercent.toFixed(2)}%

                        </span>

                    </td>

                    <td class="px-5 py-4 text-center">

                        <span class="
                            inline-flex
                            items-center
                            px-3
                            py-1
                            rounded-full
                            font-bold
                            ${getScoreClass(attendanceScore)}
                        ">

                            ${attendanceScore}

                            <span class="ml-1 font-normal">
                                / 20
                            </span>

                        </span>

                    </td>

                `;


                tbody.appendChild(row);

            }
        );


        refreshIcons();

    }


    // =====================================================
    // Back To Evaluation
    // =====================================================

    backBtn.addEventListener(
        "click",
        () => {

            window.history.back();

        }
    );


    // =====================================================
    // Submit Attendance Evaluation
    // =====================================================

    submitBtn.addEventListener(
        "click",
        async () => {

            // -------------------------------------------------
            // Get Stored Data
            // -------------------------------------------------
            const attendanceData = getAttendanceData();
            // -------------------------------------------------
            // Validate All Users
            // -------------------------------------------------
            for (const user of users) {
                if (!attendanceData[user.user_id]) {
                    await Swal.fire({
                        icon: "warning",
                        title: "មិនអាចបញ្ជូនបានទេ",
                        text:
                            `សូមបំពេញការវាយតម្លៃរបស់ ${user.name_kh || "មន្ត្រី"} ជាមុនសិន។`,
                        confirmButtonText:
                            "យល់ព្រម",
                        confirmButtonColor:
                            "#2563eb"
                    });
                    return;
                }
            }
            // Confirm Submit
            const result =
                await Swal.fire({

                    icon: "question",

                    title: "បញ្ជូនការវាយតម្លៃ?",

                    text:
                        "តើអ្នកប្រាកដទេថាចង់បញ្ជូនការវាយតម្លៃ?",

                    showCancelButton: true,

                    confirmButtonText:
                        "បញ្ជូន",

                    cancelButtonText:
                        "បោះបង់",

                    confirmButtonColor:
                        "#2563eb",

                    cancelButtonColor:
                        "#6b7280"

                });


            if (!result.isConfirmed) {
                return;
            }


            // -------------------------------------------------
            // Loading
            // -------------------------------------------------

            submitBtn.disabled = true;

            submitBtn.classList.add(
                "opacity-50",
                "cursor-not-allowed"
            );


            submitBtn.innerHTML = `

                <svg
                    class="animate-spin w-4 h-4"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                >

                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>

                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
                    ></path>

                </svg>

                កំពុងបញ្ជូន...

            `;


            // -------------------------------------------------
            // Send To Laravel
            // -------------------------------------------------

            try {

                const response =
                    await fetch(
                        window.attendanceSubmitUrl,
                        {
                            method: "POST",

                            headers: {

                                "Content-Type":
                                    "application/json",

                                "Accept":
                                    "application/json",

                                "X-CSRF-TOKEN":
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        ?.getAttribute("content")

                            },

                            body: JSON.stringify({

                                attendance:
                                    attendanceData

                            })

                        }
                    );


                const result =
                    await response.json();


                // -------------------------------------------------
                // Failed
                // -------------------------------------------------

                if (!response.ok || !result.success) {

                    throw new Error(
                        result.message
                        || "មានបញ្ហាក្នុងការបញ្ជូនទិន្នន័យ។"
                    );

                }


                // -------------------------------------------------
                // Success
                // -------------------------------------------------

                await Swal.fire({

                    icon: "success",

                    title:
                        "បញ្ជូនជោគជ័យ!",

                    text:
                        result.message
                        || "ការវាយតម្លៃត្រូវបានរក្សាទុកដោយជោគជ័យ។",

                    confirmButtonText:
                        "យល់ព្រម",

                    confirmButtonColor:
                        "#2563eb"

                });


                // -------------------------------------------------
                // Clear Session Storage
                // -------------------------------------------------

                sessionStorage.removeItem(
                    storageKey
                );
                // -------------------------------------------------
                // Redirect
                // -------------------------------------------------

                window.location.href = window.attendanceIndexUrl;
            } catch (error) {
                console.error(
                    "Attendance submit error:",
                    error
                );


                await Swal.fire({

                    icon: "error",

                    title:
                        "បញ្ជូនមិនបាន",

                    text:
                        error.message
                        || "មានបញ្ហាក្នុងការបញ្ជូនការវាយតម្លៃ។",

                    confirmButtonText:
                        "យល់ព្រម",

                    confirmButtonColor:
                        "#dc2626"

                });


                // -------------------------------------------------
                // Restore Button
                // -------------------------------------------------

                submitBtn.disabled = false;

                submitBtn.classList.remove(
                    "opacity-50",
                    "cursor-not-allowed"
                );


                submitBtn.innerHTML = `

                    <i
                        data-lucide="send"
                        class="w-4 h-4"
                    ></i>

                    បញ្ជូនការវាយតម្លៃ

                `;


                refreshIcons();

            }

        }
    );


    // =====================================================
    // Initial Render
    // =====================================================

    renderPreview();

});