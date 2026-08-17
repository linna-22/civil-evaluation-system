document.addEventListener('DOMContentLoaded', function () {

    const tableBody =
        document.getElementById('previewTableBody');

    const evaluations =
        window.behaviorEvaluations || [];


    // ==========================================
    // No Data
    // ==========================================

    if (!evaluations.length) {

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


    // ==========================================
    // Render Saved Evaluations
    // ==========================================

    tableBody.innerHTML = evaluations
        .map((evaluation, index) => {

            const peer =
                evaluation.evaluatee;

            const behavior =
                evaluation.behavior;


            const sectionOneScore =
                Number(behavior.discipline ?? 0) +
                Number(behavior.responsibility ?? 0) +
                Number(behavior.professional_ethics ?? 0);


            const sectionTwoScore =
                Number(behavior.work_performance ?? 0) +
                Number(behavior.self_development ?? 0) +
                Number(behavior.initiative_creativity ?? 0);


            const sectionThreeScore =
                Number(behavior.teamwork ?? 0) +
                Number(behavior.interpersonal_skill ?? 0) +
                Number(behavior.work_under_pressure ?? 0) +
                Number(behavior.leadership ?? 0);


            const overallScore =
                Number(behavior.total_score ?? 0);


            return `
                <tr class="hover:bg-gray-50 transition">

                    <td class="px-5 py-5 text-center text-gray-500">
                        ${index + 1}
                    </td>


                    <td class="px-5 py-5">

                        <div class="font-semibold text-gray-800">
                            ${peer?.name_kh ?? ''}
                        </div>

                        <div class="text-xs text-gray-500 mt-1">
                            ${peer?.name_en ?? ''}
                        </div>

                        <div class="text-xs text-gray-400 mt-1">
                            ${peer?.position ?? ''}
                        </div>

                    </td>


                    <td class="px-5 py-5 text-center">

                        <span
                            class="
                                inline-flex
                                items-center
                                px-3
                                py-1
                                rounded-full
                                bg-blue-50
                                text-gray-500
                                font-semibold
                            "
                        >
                            ${sectionOneScore}

                            <span class="text-gray-400 font-normal mx-1">
                                /
                            </span>

                            6
                        </span>

                    </td>


                    <td class="px-5 py-5 text-center">

                        <span
                            class="
                                inline-flex
                                items-center
                                px-3
                                py-1
                                rounded-full
                                bg-blue-50
                                text-gray-500
                                font-semibold
                            "
                        >
                            ${sectionTwoScore}

                            <span class="text-gray-400 font-normal mx-1">
                                /
                            </span>

                            6
                        </span>

                    </td>


                    <td class="px-5 py-5 text-center">

                        <span
                            class="
                                inline-flex
                                items-center
                                px-3
                                py-1
                                rounded-full
                                bg-blue-50
                                text-gray-500
                                font-semibold
                            "
                        >
                            ${sectionThreeScore}

                            <span class="text-gray-400 font-normal mx-1">
                                /
                            </span>

                            8
                        </span>

                    </td>


                    <td class="px-5 py-5 text-center">

                        <span
                            class="
                                inline-flex
                                items-center
                                px-3
                                py-1
                                rounded-full
                                bg-blue-50
                                text-blue-700
                                font-semibold
                            "
                        >
                            ${overallScore}

                            <span class="text-gray-400 font-normal mx-1">
                                / 20
                            </span>

                        </span>

                    </td>

                </tr>
            `;

        })
        .join('');

});