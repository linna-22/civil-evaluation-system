import Swal from "sweetalert2";
document.addEventListener('DOMContentLoaded', function () {

    const tableBody =
        document.getElementById('previewTableBody');

    const overallSummary =
        document.getElementById('overallSummary');

    const confirmButton =
        document.getElementById('confirmButton');


    // Get saved evaluation data
    const storedEvaluationData =
        sessionStorage.getItem('behaviorEvaluationData');


    // No data
    if (!storedEvaluationData) {

        tableBody.innerHTML = `
            <tr>
                <td
                    colspan="6"
                    class="px-6 py-12 text-center text-gray-500"
                >
                    មិនមានទិន្នន័យការវាយតម្លៃ
                </td>
            </tr>
        `;

        return;
    }


    // Parse saved data
    const evaluationData =
        JSON.parse(storedEvaluationData);


    const peers =
        evaluationData.peers || [];


    const answers =
        evaluationData.answers || {};


    /*
    |--------------------------------------------------------------------------
    | Render Table
    |--------------------------------------------------------------------------
    */



    tableBody.innerHTML = peers
        .map((peer, index) => {

            const peerAnswers =
                answers[peer.user_id] || {};
            /*
            |--------------------------------------------------------------------------
            | Section 1
            |--------------------------------------------------------------------------
            */
            const sectionOneScore =
                Number(peerAnswers.discipline ?? 0) +
                Number(peerAnswers.responsibility ?? 0) +
                Number(peerAnswers.professional_ethics ?? 0);
            const sectionOneMax = 6;
            /*
            |--------------------------------------------------------------------------
            | Section 2
            |--------------------------------------------------------------------------
            */
            const sectionTwoScore =
                Number(peerAnswers.work_performance ?? 0) +
                Number(peerAnswers.self_development ?? 0) +
                Number(peerAnswers.initiative_creativity ?? 0);
            const sectionTwoMax = 6;
            /*
            |--------------------------------------------------------------------------
            | Section 3
            |--------------------------------------------------------------------------
            */
            const sectionThreeScore =
                Number(peerAnswers.teamwork ?? 0) +
                Number(peerAnswers.interpersonal_skill ?? 0) +
                Number(peerAnswers.work_under_pressure ?? 0) +
                Number(peerAnswers.leadership ?? 0);
            const sectionThreeMax = 8;
            /*
            |--------------------------------------------------------------------------
            | Overall
            |--------------------------------------------------------------------------
            */
            const overallScore =
                sectionOneScore +
                sectionTwoScore +
                sectionThreeScore;
            const overallMax =
                sectionOneMax +
                sectionTwoMax +
                sectionThreeMax;
            return `
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-5 text-center text-gray-500">
                        ${index + 1}
                    </td>
                    <td class="px-5 py-5">
                        <div class="font-semibold text-gray-800">
                            ${peer.name_kh ?? ''}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            ${peer.name_en ?? ''}
                        </div>
                        <div class="text-xs text-gray-400 mt-1">
                            ${peer.position ?? ''}
                        </div>
                    </td>
                    <td class="px-5 py-5 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-gray-500 font-semibold">
                            ${sectionOneScore}
                            <span class="text-gray-400 font-normal mx-1">
                                /
                            </span>
                            ${sectionOneMax}
                        </span>
                    </td>
                    <td class="px-5 py-5 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-gray-500 font-semibold">
                            ${sectionTwoScore}
                            <span class="text-gray-400 font-normal mx-1">
                                /
                            </span>
                            ${sectionTwoMax}
                        </span>
                    </td>
                    <td class="px-5 py-5 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-gray-500 font-semibold">
                            ${sectionThreeScore}
                            <span class="text-gray-400 font-normal mx-1">
                                /
                            </span>
                            ${sectionThreeMax}
                        </span>
                    </td>
                    <td class="px-5 py-5 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-700 font-semibold">
                            ${overallScore}
                            <span class="text-gray-400 font-normal mx-1">
                                / ${overallMax}
                            </span>
                        </span>
                    </td>
                </tr>
            `;

        })
        .join('');
    const evaluations =
        peers.map(peer => {

            const peerAnswers =
                answers[peer.user_id] || {};

            return {
                evaluatee_id: peer.user_id,

                discipline:
                    Number(peerAnswers.discipline ?? 0),

                responsibility:
                    Number(peerAnswers.responsibility ?? 0),

                professional_ethics:
                    Number(peerAnswers.professional_ethics ?? 0),

                work_performance:
                    Number(peerAnswers.work_performance ?? 0),

                self_development:
                    Number(peerAnswers.self_development ?? 0),

                initiative_creativity:
                    Number(peerAnswers.initiative_creativity ?? 0),

                teamwork:
                    Number(peerAnswers.teamwork ?? 0),

                interpersonal_skill:
                    Number(peerAnswers.interpersonal_skill ?? 0),

                work_under_pressure:
                    Number(peerAnswers.work_under_pressure ?? 0),

                leadership:
                    Number(peerAnswers.leadership ?? 0),
            };

        });
    console.log(
        'Evaluations to submit:',
        evaluations
    );
    confirmButton.addEventListener('click', async function () {

        try {

            // Prevent double submission
            confirmButton.disabled = true;

            const response = await fetch(
                '/evaluations/behavior',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN':
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute('content'),

                        'Accept': 'application/json',
                    },

                    body: JSON.stringify({
                        evaluations: evaluations
                    }),
                }
            );


            const result =
                await response.json();


            // ==========================================
            // Success
            // ==========================================

            if (
                response.ok &&
                result.success
            ) {

                // Clear temporary evaluation data
                sessionStorage.removeItem(
                    'behaviorEvaluationData'
                );


                await Swal.fire({

                    icon: 'success',

                    title: 'រក្សាទុកដោយជោគជ័យ',

                    text: result.message,

                    confirmButtonText: 'យល់ព្រម',

                    confirmButtonColor: '#2563eb',

                });


                // Redirect to behavior index
                window.location.href =
                    '/evaluations/behavior';

                return;
            }


            // ==========================================
            // Server error
            // ==========================================

            throw new Error(
                result.message ||
                'មានបញ្ហាក្នុងការរក្សាទុកការវាយតម្លៃ។'
            );


        } catch (error) {

            console.error(
                'Submit error:',
                error
            );


            Swal.fire({

                icon: 'error',

                title: 'មានបញ្ហា',

                text:
                    error.message ||
                    'មិនអាចបញ្ជូនការវាយតម្លៃបានទេ។',

                confirmButtonText: 'យល់ព្រម',

            });


            // Enable button again
            confirmButton.disabled = false;

        }

    });

});