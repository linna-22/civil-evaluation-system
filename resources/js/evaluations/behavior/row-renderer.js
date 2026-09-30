export function renderBehaviorRow(peer, index) {

    const isSubmitted =
        peer.evaluation_status === "submitted";


    const gender =
        peer.gender === "female"
            ? "ស្រី"
            : "ប្រុស";


    const statusHtml = isSubmitted
        ? `
            <span
                class="
                    inline-flex
                    items-center
                    gap-2
                    px-3
                    py-1.5
                    rounded-full
                    bg-green-50
                    text-green-700
                    text-xs
                    font-medium
                ">

                <i
                    data-lucide="circle-check"
                    class="w-4 h-4">
                </i>

                បានវាយតម្លៃរួចរាល់

            </span>
        `
        : `
            <span
                class="
                    inline-flex
                    items-center
                    gap-2
                    px-3
                    py-1.5
                    rounded-full
                    bg-red-50
                    text-red-600
                    text-xs
                    font-medium
                ">

                <i
                    data-lucide="clock-3"
                    class="w-4 h-4">
                </i>

                រង់ចាំការវាយតម្លៃ

            </span>
        `;


    return `
        <tr class="hover:bg-gray-50 transition">

            <td class="px-6 py-4 text-gray-500">
                ${index}
            </td>

            <td class="px-6 py-4">

                <div>

                    <p class="font-medium text-gray-800">
                        ${peer.name_kh ?? "-"}
                    </p>

                    <p class="text-sm text-gray-500">
                        ${peer.name_en ?? "-"}
                    </p>

                </div>

            </td>

            <td class="px-6 py-4 text-gray-600">
                ${gender}
            </td>

            <td class="px-6 py-4 text-gray-600">
                ${peer.position ?? "-"}
            </td>

            <td class="px-6 py-4">
                ${statusHtml}
            </td>

        </tr>
    `;
}