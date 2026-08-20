export function registerUserEvents() {

    document.addEventListener("click", (e) => {

        const editButton = e.target.closest(".btn-edit");

        if (editButton) {

            const id = editButton.dataset.id;

            window.location.href = `/users/${id}/edit`;

        }

    });

}
