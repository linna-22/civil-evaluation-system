import { patch } from "../../utils/api";
import Swal from "sweetalert2";


export function registerEvaluationPeriodEvents(table) {

    document.addEventListener("click", async (e) => {

        // Details
        const viewButton = e.target.closest(
            ".btn-view-evaluation"
        );

        if (viewButton) {

            const id = viewButton.dataset.id;

            window.location.href =
                `/evaluation-periods/${id}`;

            return;
        }

        // ==========================================
        // Edit
        // ==========================================

        const editButton = e.target.closest(".btn-edit");

        if (editButton) {

            const id = editButton.dataset.id;

            window.location.href =
                `/evaluation-periods/${id}/edit`;

            return;
        }


        // ==========================================
        // Close Evaluation
        // ==========================================

        const closeButton = e.target.closest(
            ".btn-close-evaluation"
        );

        if (!closeButton) {
            return;
        }


        const id = closeButton.dataset.id;


        // ==========================================
        // Confirmation
        // ==========================================

        const result = await Swal.fire({

            title: "បិទការវាយតម្លៃ",

            text: "តើអ្នកពិតជាចង់បិទការវាយតម្លៃនេះមែនទេ?",

            icon: "warning",

            showCancelButton: true,

            confirmButtonText: "បិទការវាយតម្លៃ",

            cancelButtonText: "បោះបង់",

            reverseButtons: true,

        });


        if (!result.isConfirmed) {
            return;
        }


        // ==========================================
        // Loading
        // ==========================================

        Swal.fire({

            title: "កំពុងបិទការវាយតម្លៃ...",

            allowOutsideClick: false,

            allowEscapeKey: false,

            didOpen: () => {

                Swal.showLoading();

            },

        });


        try {

            closeButton.disabled = true;


            // ==========================================
            // Close Evaluation
            // ==========================================

            const response = await patch(
                `/evaluation-periods/${id}/close`
            );


            // ==========================================
            // Success
            // ==========================================

            if (response.success) {

                await Swal.fire({

                    icon: "success",

                    title: "ជោគជ័យ",

                    text: response.message,

                    timer: 1500,

                    showConfirmButton: false,

                });


                // Reload current page

                table.load(
                    table.state.page
                );

            }


        } catch (error) {

            console.error(error);


            // ==========================================
            // Error
            // ==========================================

            Swal.fire({

                icon: "error",

                title: "មានបញ្ហា",

                text:
                    error.message ||
                    "មិនអាចបិទវគ្គវាយតម្លៃបានទេ។",

            });


        } finally {

            closeButton.disabled = false;

        }

    });

}