import Swal from "sweetalert2";
import { refreshIcons } from "../../utils/lucide";

document.addEventListener("DOMContentLoaded", () => {

    // =====================================================
    // Get Stored Evaluation Data
    // =====================================================

    const storedData =
        sessionStorage.getItem(
            "workPerformanceEvaluationData"
        );


    if (!storedData) {

        console.warn(
            "No work performance evaluation data found."
        );

        return;
    }


    // =====================================================
    // Parse Data
    // =====================================================

    let evaluationData;

    try {

        evaluationData =
            JSON.parse(storedData);

    } catch (error) {

        console.error(
            "Invalid evaluation data:",
            error
        );

        return;
    }


    // =====================================================
    // Data
    // =====================================================

    const users =
        evaluationData.users || [];

    const answers =
        evaluationData.answers || {};


    // =====================================================
    // Elements
    // =====================================================

    const previewUsers =
        document.getElementById(
            "previewUsers"
        );

    const totalUsers =
        document.getElementById(
            "totalUsers"
        );

    const evaluationSummary =
        document.getElementById(
            "evaluationSummary"
        );

    const backButton =
        document.getElementById(
            "backToEvaluationBtn"
        );

    const submitButton =
        document.getElementById(
            "submitEvaluationBtn"
        );


    if (!previewUsers) {
        return;
    }


    // =====================================================
    // Summary
    // =====================================================

    if (totalUsers) {

        totalUsers.textContent =
            users.length;

    }


    if (evaluationSummary) {

        evaluationSummary.textContent =
            `មានមន្ត្រីចំនួន ${users.length} នាក់`;

    }


    // =====================================================
    // Calculate Work Performance
    // =====================================================

    // =====================================================
    // Calculate Work Performance
    // =====================================================

    function calculateWorkPerformance(performances) {

        if (!performances.length) {

            return {
                totalScore: 0,
                evaluationScore: 0
            };

        }


        // -------------------------------------------------
        // Equal Weight
        // -------------------------------------------------

        const weight =
            100 / performances.length;


        let totalScore = 0;


        // -------------------------------------------------
        // Calculate Each Activity
        // -------------------------------------------------

        performances.forEach(
            performance => {

                let achievement =
                    Number(
                        performance.achievement_percent
                    ) || 0;


                // Keep achievement between 0 - 100

                achievement =
                    Math.min(
                        100,
                        Math.max(
                            0,
                            achievement
                        )
                    );


                // -------------------------------------------------
                // Each activity contributes according
                // to its equal weight.
                // -------------------------------------------------

                const rowScore = (achievement * weight) / 100;
                totalScore += rowScore;

            }
        );

        // Total Score
        totalScore = Math.min(100, Math.max(0, totalScore));
        // Evaluation Score / 60
        let evaluationScore = 0;
        if (totalScore <= 60) {
            evaluationScore = 0;
        } else if (totalScore > 60 && totalScore <= 70) {
            evaluationScore = 15;
        } else if (totalScore > 70 && totalScore <= 80) {
            evaluationScore = 30;
        } else if (totalScore > 80 && totalScore <= 90) {
            evaluationScore = 45;
        } else if (totalScore > 90 && totalScore <= 100) {
            evaluationScore = 60;
        }

        // Return Result

        return {
            totalScore: Number(totalScore.toFixed(2)),
            evaluationScore: evaluationScore
        };

    }


    // =====================================================
    // Format Score
    // =====================================================

    function formatScore(score) {

        const number =
            Number(score) || 0;


        if (
            Number.isInteger(number)
        ) {

            return number.toString();

        }


        return number.toFixed(2);

    }


    // =====================================================
    // Render Users
    // =====================================================

    previewUsers.innerHTML = "";


    users.forEach(
        (user, userIndex) => {

            // -------------------------------------------------
            // Get This User's Data
            // -------------------------------------------------

            const userData =
                answers[user.user_id] || {};


            const performances =
                userData.performances || [];


            // -------------------------------------------------
            // Calculate This User
            // -------------------------------------------------

            const result =
                calculateWorkPerformance(
                    performances
                );


            // -------------------------------------------------
            // Create Row
            // -------------------------------------------------

            const row =
                document.createElement("tr");


            row.className =
                "hover:bg-gray-50 transition";


            row.innerHTML = `

                <!-- Number -->

                <td
                    class="
                        px-5
                        py-4
                        text-center
                        text-gray-700
                        whitespace-nowrap
                    "
                >
                    ${userIndex + 1}
                </td>


                <!-- User Name -->

                <td
                    class="
                        px-5
                        py-4
                        text-left
                        font-medium
                        text-gray-800
                        whitespace-nowrap
                    "
                >
                    ${user.name_kh || "-"}
                </td>


                <!-- Position -->

                <td
                    class="
                        px-5
                        py-4
                        text-left
                        text-gray-600
                        whitespace-nowrap
                    "
                >
                    ${user.position || "-"}
                </td>


                <!-- Activity Count -->

                <td
                    class="
                        px-5
                        py-4
                        text-center
                        text-gray-700
                        whitespace-nowrap
                    "
                >
                    <span
                        class="
                            inline-flex
                            items-center
                            justify-center
                            min-w-8
                            px-2
                            py-1
                            rounded-full
                            bg-blue-50
                            text-blue-700
                            text-sm
                            font-medium
                        "
                    >
                        ${performances.length}
                    </span>
                </td>


                <!-- Total Score -->

                <td
                    class="
                        px-5
                        py-4
                        text-center
                        font-semibold
                        text-blue-600
                        whitespace-nowrap
                    "
                >
                    ${formatScore(
                result.totalScore
            )}
                </td>


                <!-- Evaluation Score -->

                <td
                    class="
                        px-5
                        py-4
                        text-center
                        font-semibold
                        text-blue-600
                        whitespace-nowrap
                    "
                >
                    ${formatScore(
                result.evaluationScore
            )}
                    / 60
                </td>

            `;


            previewUsers.appendChild(
                row
            );

        }
    );


    // =====================================================
    // Refresh Icons
    // =====================================================

    refreshIcons();


    // =====================================================
    // Back To Evaluation
    // =====================================================

    if (backButton) {

        backButton.addEventListener("click", () => {

            const officeId =
                window.workPerformanceOfficeId;

            if (officeId !== null && officeId !== undefined) {

                window.location.href =
                    `/evaluations/work-performance/create/${officeId}`;

            } else {

                window.location.href =
                    "/evaluations/work-performance/create";

            }

        });

    }


    // =====================================================
    // Submit Evaluation
    // =====================================================

    if (submitButton) {

        submitButton.addEventListener(
            "click",
            async () => {

                // -------------------------------------------------
                // Prepare data
                // -------------------------------------------------

                const submitData = {
                    users: users.map(user => ({
                        user_id: user.user_id,
                        answers: answers[user.user_id] || {
                            performances: []
                        }
                    }))
                };

                // Loading state
                submitButton.disabled = true;
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = `កំពុងបញ្ជូន...`;


                try {

                    // -------------------------------------------------
                    // Send to Laravel
                    // -------------------------------------------------

                    const response =
                        await fetch(
                            "/evaluations/work-performance/submit",
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
                                            ?.getAttribute(
                                                "content"
                                            )
                                },

                                body:
                                    JSON.stringify(
                                        submitData
                                    )
                            }
                        );
                    const result = await response.json();
                    // Error
                    if (!response.ok || !result.success) {
                        throw new Error(
                            result.message || "មានបញ្ហាក្នុងការបញ្ជូនទិន្នន័យ។"
                        );
                    }
                    // Success
                    await Swal.fire({
                        icon: "success",
                        title: "បញ្ជូនជោគជ័យ",
                        text: result.message || "ការវាយតម្លៃត្រូវបានរក្សាទុកដោយជោគជ័យ។",
                        confirmButtonText: "យល់ព្រម",
                        confirmButtonColor: "#2563eb"

                    });
                    // Clear temporary data
                    sessionStorage.removeItem("workPerformanceEvaluationData");
                    // Go back to Work Performance page
                    window.location.href = "/evaluations/work-performance";
                } catch (error) {
                    console.error("Submit evaluation error:", error);
                    await Swal.fire({
                        icon: "error",
                        title: "បញ្ជូនមិនបានជោគជ័យ",
                        text: error.message || "មានបញ្ហាក្នុងការបញ្ជូនទិន្នន័យ។",
                        confirmButtonText: "យល់ព្រម",
                        confirmButtonColor: "#dc2626"

                    });


                } finally {

                    // Restore button
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                    refreshIcons();
                }

            }
        );

    }

});