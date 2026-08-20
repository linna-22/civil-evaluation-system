document.addEventListener("DOMContentLoaded", () => {

    const forms = document.querySelectorAll("form");

    forms.forEach(form => {

        form.addEventListener("submit", function (e) {

            let valid = true;

            const requiredFields = form.querySelectorAll("[required]");

            requiredFields.forEach(field => {

                removeError(field);

                if (field.value.trim() === "") {

                    showError(field, "This field is required.");

                    valid = false;

                }

            });

            if (!valid) {

                e.preventDefault();

            }

        });

    });

    function showError(field, message) {

        field.classList.add(
            "border-red-500",
            "focus:ring-red-100",
            "focus:border-red-500"
        );

        const error = document.createElement("p");

        error.className =
            "mt-1 text-sm text-red-500 validation-error";

        error.innerText = message;

        field.parentNode.appendChild(error);

    }

    function removeError(field) {

        field.classList.remove(
            "border-red-500",
            "focus:ring-red-100",
            "focus:border-red-500"
        );

        const error = field.parentNode.querySelector(".validation-error");

        if (error) {

            error.remove();

        }

    }

    document.addEventListener("input", function (e) {

        if (e.target.hasAttribute("required")) {

            if (e.target.value.trim() !== "") {

                removeError(e.target);

            }

        }

    });

});