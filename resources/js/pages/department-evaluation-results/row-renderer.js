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
                ${user?.name_en ?? "មិនមាន"}
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

        </tr>
    `;
}