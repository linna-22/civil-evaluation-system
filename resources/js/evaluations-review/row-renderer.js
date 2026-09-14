export function renderEvaluationReviewRow(user, index) {
    return `
        <tr class="hover:bg-gray-50 transition">

            <td class="px-6 py-4 text-gray-500">
                ${index}
            </td>

            <td class="px-6 py-4">
                <p class="font-medium text-gray-800">
                    ${user.name_kh ?? "-"}
                </p>
            </td>

            <td class="px-6 py-4 text-gray-600">
                ${user.name_en ?? "-"}
            </td>

            <td class="px-6 py-4 text-gray-600">
                ${user.position ?? "-"}
            </td>

            <td class="px-3 py-4">

                <span
                    class="
                        inline-flex
                        items-center
                        gap-2
                        px-3
                        py-1.5
                        rounded-full
                        bg-yellow-50
                        text-yellow-500
                        text-xs
                        font-medium
                    "
                >
                    <i
                        data-lucide="clock-3"
                        class="w-4 h-4">
                    </i>

                    រងចាំការត្រួតពិនិត្យ
                </span>

            </td>

            <td class="px-6 py-4 text-center">

                <a
                    href="/evaluations/review/${user.user_id}"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        px-4
                        py-2
                        rounded-lg
                        bg-blue-600
                        text-white
                        text-sm
                        font-medium
                        hover:bg-blue-700
                        transition
                    "
                >
                    <i
                        data-lucide="eye"
                        class="w-4 h-4">
                    </i>

                    ពិនិត្យ
                </a>

            </td>

        </tr>
    `;
}