import {
    statusBadge,
    actionButtons,
    EvaluationPeriodstatusBadge,
    evaluationPeriodActionButtons
} from "../../components/data-table/helpers";
export function renderEvaluationPeriodRow(evaluation_period) {

    return `
        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

            <td class="px-6 py-4">${evaluation_period.evaluation_period_id}</td>

            <td class="px-6 py-4">
                ${evaluation_period.name_kh}
            </td>

            <td class="px-6 py-4">
                ${evaluation_period.name_en}
            </td>
            <td class="px-6 py-4">
                ខែ${evaluation_period.month} ឆ្នាំ${evaluation_period.year}
            </td>
            <td class="px-6 py-4">
                ${EvaluationPeriodstatusBadge(evaluation_period.status)}
            </td>

            <td class="px-6 py-4">
                 ${evaluationPeriodActionButtons(
                    evaluation_period.evaluation_period_id,
                    evaluation_period.status
                )}
            </td>

        </tr>
    `;
}