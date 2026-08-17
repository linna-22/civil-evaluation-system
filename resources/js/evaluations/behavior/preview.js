document.addEventListener('DOMContentLoaded', function () {

    const tableBody =
        document.getElementById('previewTableBody');

    const overallSummary =
        document.getElementById('overallSummary');


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

});