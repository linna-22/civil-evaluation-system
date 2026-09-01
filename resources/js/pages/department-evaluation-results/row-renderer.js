export function renderDepartmentResultRow(result, no) {

    const user =
        result.evaluation_period_user?.user;

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

            <td class="px-6 py-4">
                ${user?.position ?? "មិនមាន"}
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

        </tr>
    `;
}