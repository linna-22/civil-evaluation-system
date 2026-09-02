export function registerDepartmentEvents(table) {

    const modal = document.querySelector("#remarks-modal");
    const backdrop = document.querySelector("#remarks-modal-backdrop");
    const closeButton = document.querySelector("#remarks-modal-close");
    const cancelButton = document.querySelector("#remarks-modal-cancel");

    const title = document.querySelector("#remarks-modal-title");
    const employeeName = document.querySelector("#remarks-modal-employee");
    const input = document.querySelector("#remarks-input");
    const summaryId = document.querySelector(
        "#remarks-evaluation-summary-id"
    );

    if (!modal) {
        return;
    }

    // ==========================
    // Open Modal
    // ==========================

    document.addEventListener("click", (e) => {

        const remarkButton = e.target.closest(".btn-remark");

        if (!remarkButton) {
            return;
        }

        const id = remarkButton.dataset.id;
        const nameKh = remarkButton.dataset.nameKh;
        const remark = remarkButton.dataset.remark || "";

        summaryId.value = id;
        employeeName.textContent = `មន្ត្រី: ${nameKh}`;
        input.value = remark;

        if (remark.trim() !== "") {
            title.textContent = "កែប្រែមូលវិចារណ៍";
        } else {
            title.textContent = "បន្ថែមមូលវិចារណ៍";
        }

        modal.classList.remove("hidden");
        modal.setAttribute("aria-hidden", "false");

        input.focus();
    });

    // ==========================
    // Close Modal
    // ==========================

    function closeModal() {

        modal.classList.add("hidden");
        modal.setAttribute("aria-hidden", "true");

        input.value = "";
        summaryId.value = "";
    }

    closeButton?.addEventListener("click", closeModal);

    cancelButton?.addEventListener("click", closeModal);

    backdrop?.addEventListener("click", closeModal);

    // ==========================
    // Escape Key
    // ==========================

    document.addEventListener("keydown", (e) => {

        if (e.key === "Escape" && !modal.classList.contains("hidden")) {
            closeModal();
        }

    });

    // ==========================
    // Save
    // ==========================

    document.addEventListener("click", async (e) => {

        const saveButton = e.target.closest("#remarks-modal-save");

        if (!saveButton) {
            return;
        }

        const id = summaryId.value;
        const remark = input.value.trim();

        saveButton.disabled = true;
        saveButton.textContent = "កំពុងរក្សាទុក...";

        try {

            const response = await fetch(
                `/department-evaluation-results/remarks/${id}`,
                {
                    method: "PATCH",

                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },

                    body: JSON.stringify({
                        remarks: remark || null,
                    }),
                }
            );

            const data = await response.json();

            if (!response.ok) {
                throw new Error(
                    data.message || "Failed to save remark."
                );
            }

            // console.log(data.message);

            closeModal();

            // Reload table so the new remark appears.
            await table.load();

        } catch (error) {

            console.error(error);

            alert(
                error.message ||
                "មិនអាចរក្សាទុកមូលវិចារណ៍បានទេ។"
            );

        } finally {

            saveButton.disabled = false;
            saveButton.textContent = "រក្សាទុក";
        }
    });
}