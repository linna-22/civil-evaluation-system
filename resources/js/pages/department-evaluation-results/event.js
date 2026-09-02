import Swal from "sweetalert2";
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

    const selectedValue = document.querySelector(
        "#remarks-selected-value"
    );

    const manualOption = document.querySelector(
        "#remarks-manual-option"
    );

    const manualInputWrapper = document.querySelector(
        "#remarks-manual-input-wrapper"
    );

    const remarkOptions = document.querySelectorAll(
        ".remark-option"
    );

    if (!modal) {
        return;
    }


    // ==========================
    // Select Remark Option
    // ==========================

    function selectRemarkOption(value) {
        remarkOptions.forEach((button) => {
            const isSelected = button.dataset.value === value;

            button.classList.toggle("border-blue-500", isSelected);
            button.classList.toggle("bg-blue-50", isSelected);
            button.classList.toggle("text-blue-600", isSelected);

            button.classList.toggle("border-gray-300", !isSelected);
            button.classList.toggle("text-gray-700", !isSelected);
        });

        selectedValue.value = value;

        if (value === "manual") {
            // Clear the previous predefined remark
            input.value = "";

            manualInputWrapper.classList.remove("hidden");
            input.focus();
        } else {
            // Hide manual input
            manualInputWrapper.classList.add("hidden");
        }
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

        employeeName.textContent =
            `មន្ត្រី: ${nameKh}`;

        input.value = remark;


        // ==========================
        // Modal Title
        // ==========================

        if (remark.trim() !== "") {

            title.textContent =
                "កែប្រែមូលវិចារណ៍";

        } else {

            title.textContent =
                "បន្ថែមមូលវិចារណ៍";
        }


        // ==========================
        // Determine Existing Remark
        // ==========================

        const predefinedRemarks = [
            "ល្អណាស់",
            "ល្អបង្គួរ",
            "មធ្យម",
            "ខ្សោយ",
        ];

        if (predefinedRemarks.includes(remark.trim())) {

            selectRemarkOption(remark.trim());

        } else if (remark.trim() !== "") {

            // Existing custom remark
            selectRemarkOption("manual");

        } else {

            // No remark yet
            selectedValue.value = "";

            remarkOptions.forEach((button) => {

                button.classList.remove(
                    "border-blue-500",
                    "bg-blue-50",
                    "text-blue-600"
                );

                button.classList.add(
                    "border-gray-300",
                    "text-gray-700"
                );
            });

            manualInputWrapper.classList.add("hidden");
            input.value = "";
        }


        // ==========================
        // Show Modal
        // ==========================

        modal.classList.remove("hidden");

        modal.setAttribute(
            "aria-hidden",
            "false"
        );
    });


    // ==========================
    // Click Remark Option
    // ==========================

    remarkOptions.forEach((button) => {

        button.addEventListener("click", () => {

            const value = button.dataset.value;

            selectRemarkOption(value);
        });
    });


    // ==========================
    // Close Modal
    // ==========================

    function closeModal() {

        modal.classList.add("hidden");

        modal.setAttribute(
            "aria-hidden",
            "true"
        );

        input.value = "";

        summaryId.value = "";

        selectedValue.value = "";

        manualInputWrapper.classList.add(
            "hidden"
        );

        remarkOptions.forEach((button) => {

            button.classList.remove(
                "border-blue-500",
                "bg-blue-50",
                "text-blue-600"
            );

            button.classList.add(
                "border-gray-300",
                "text-gray-700"
            );
        });
    }


    closeButton?.addEventListener(
        "click",
        closeModal
    );

    cancelButton?.addEventListener(
        "click",
        closeModal
    );

    backdrop?.addEventListener(
        "click",
        closeModal
    );


    // ==========================
    // Escape Key
    // ==========================

    document.addEventListener("keydown", (e) => {

        if (
            e.key === "Escape" &&
            !modal.classList.contains("hidden")
        ) {
            closeModal();
        }

    });


    // ==========================
    // Save
    // ==========================

    document.addEventListener("click", async (e) => {

        const saveButton =
            e.target.closest("#remarks-modal-save");

        if (!saveButton) {
            return;
        }

        const id = summaryId.value;

        const selected = selectedValue.value;

        let remark = "";


        // ==========================
        // Get Selected Remark
        // ==========================

        if (selected === "manual") {

            remark = input.value.trim();

        } else {

            remark = selected;
        }


        saveButton.disabled = true;

        saveButton.textContent =
            "កំពុងរក្សាទុក...";


        try {

            const response = await fetch(
                `/department-evaluation-results/remarks/${id}`,
                {
                    method: "PATCH",

                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",

                        "X-CSRF-TOKEN":
                            document
                                .querySelector(
                                    'meta[name="csrf-token"]'
                                )
                                .getAttribute("content"),
                    },

                    body: JSON.stringify({
                        remarks: remark || null,
                    }),
                }
            );


            const data =
                await response.json();


            if (!response.ok) {

                throw new Error(
                    data.message ||
                    "Failed to save remark."
                );
            }
            closeModal();
            await table.load();
            await Swal.fire({
                icon: "success",
                title: "ជោគជ័យ",
                text: "មូលវិចារណ៍ត្រូវបានរក្សាទុកដោយជោគជ័យ។",
                confirmButtonText: "យល់ព្រម",
            });


        } catch (error) {

            console.error(error);

            alert(
                error.message ||
                "មិនអាចរក្សាទុកមូលវិចារណ៍បានទេ។"
            );


        } finally {

            saveButton.disabled = false;

            saveButton.textContent =
                "រក្សាទុក";
        }
    });
}