export function actionButtons(id) {

    return `
        <div class="flex items-center justify-center">

            <button
                type="button"
                class="btn-edit
                    flex
                    items-center
                    justify-center
                    h-9
                    w-9
                    rounded-lg
                    bg-amber-100
                    text-amber-600
                    hover:bg-amber-200
                    cursor-pointer"
                data-id="${id}">

                <i data-lucide="square-pen" class="w-4 h-4"></i>

            </button>

        </div>
    `;

}