document.addEventListener("DOMContentLoaded", () => {

    document.querySelectorAll("[data-open-modal]").forEach(button => {

        button.addEventListener("click", () => {

            const id = button.dataset.openModal;

            document
                .getElementById(id)
                ?.classList.remove("hidden");

        });

    });

    document.querySelectorAll("[data-close-modal]").forEach(button => {

        button.addEventListener("click", () => {

            button
                .closest(".fixed")
                ?.classList.add("hidden");

        });

    });

});