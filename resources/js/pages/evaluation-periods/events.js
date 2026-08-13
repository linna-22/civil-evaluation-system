export function registerEvaluationPeriodEvents() {

    document.addEventListener("click", (e) => {

        const editButton = e.target.closest(".btn-edit");

        if (editButton) {

            const id = editButton.dataset.id;

            window.location.href = `/evaluation-periods/${id}/edit`;

        }

    });

}
