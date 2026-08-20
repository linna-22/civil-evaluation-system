export function registerOrganizationEvents() {

    document.addEventListener("click", (e) => {

        const editButton = e.target.closest(".btn-edit");

        if (editButton) {

            const id = editButton.dataset.id;

            window.location.href = `/organizations/${id}/edit`;

        }

    });

}
