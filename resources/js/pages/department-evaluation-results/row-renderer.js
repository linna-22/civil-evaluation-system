export function renderDepartmentResultRow(result, no) {

    const user =
        result.evaluation_period_user?.user;

    const hasRemark =
        result.remarks && result.remarks.trim() !== "";

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">
                ${no}
            </td>

            <td class="px-6 py-4">
                ${user?.name_kh ?? "មិនមាន"}
            </td>

            <td class="px-6 py-4">
                ${user?.gender === 'male' ? 'ប្រុស' : user?.gender === 'female' ? 'ស្រី' : 'មិនមាន'}
            </td>

            <td class="px-6 py-4 text-center">
                ${result.work_performance_score ?? "0.00"}
            </td>

            <td class="px-6 py-4 text-center">
                ${result.attendance_score ?? "0.00"}
            </td>

            <td class="px-6 py-4 text-center">
                ${result.behavior_score ?? "0.00"}
            </td>

            <td class="px-6 py-4 text-center font-bold">
                ${result.total_score ?? "0.00"}
            </td>

            <td class="px-6 py-4 whitespace-nowrap">

                <button
                    type="button"
                    class="btn-remark px-3 py-2 rounded-lg text-sm
                           ${hasRemark
                                ? "bg-blue-50 text-blue-600 hover:bg-blue-100"
                                : "bg-gray-50 text-gray-600 hover:bg-gray-100"
                           }
                           transition"
                    data-id="${result.evaluation_summary_id}"
                    data-name-kh="${user?.name_kh ?? "មិនមាន"}"
                    data-remark="${hasRemark ? result.remarks : ""}"
                >
                    ${hasRemark ? "✎ កែប្រែ" : "+ បន្ថែម"}
                </button>

            </td>
            <td class="px-6 py-5 text-center">
                <div class="flex items-center justify-center gap-2">
                    <a
                        href="/department-evaluation-results/${window.departmentEvaluationPeriodId}/user/${user?.user_id}/print"
                        target="_blank"
                        class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-red-600 text-white text-xs hover:bg-red-700 transition"
                    >
                        <i data-lucide="file-down" class="w-3.5 h-3.5"></i>
                        PDF
                    </a>
                    <a
                        href="/department-evaluation-results/${window.departmentEvaluationPeriodId}/user/${user?.user_id}/word"
                        class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-blue-600 text-white text-xs hover:bg-blue-700 transition"
                    >
                        <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                        Word
                    </a>

                </div>
            </td>
        </tr>
    `;
}