import { deleteRecord } from "../../components/crud/Delete";

export function registerOfficeEvents(table) {

    document.addEventListener("click", async (e) => {

        // Edit
        const editButton = e.target.closest(".btn-edit");

        if (editButton) {

            const id = editButton.dataset.id;

            window.location.href = `/offices/${id}/edit`;

            return;
        }

        // Delete
        const deleteButton = e.target.closest(".btn-delete");

        if (deleteButton) {
            console.log("Delete button clicked!");

            const id = deleteButton.dataset.id;

            await deleteRecord(

                `/offices/${id}`,

                () => table.load()

            );

        }

    });

}