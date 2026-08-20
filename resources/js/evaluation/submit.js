import axios from "axios";
import Swal from "sweetalert2";

export async function submitEvaluation() {

    const form = document.getElementById("evaluationForm");

    if (!form) {
        console.error("Evaluation form not found.");
        return;
    }

    const formData = new FormData(form);

    // Confirm before submit
    const result = await Swal.fire({
        title: "បញ្ជាក់ការដាក់បញ្ជូន",
        text: "តើអ្នកពិតជាចង់ដាក់បញ្ជូនការវាយតម្លៃនេះមែនទេ?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "ដាក់បញ្ជូន",
        cancelButtonText: "បោះបង់",
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#6b7280",
        reverseButtons: true,
    });

    if (!result.isConfirmed) {
        return;
    }

    try {

        // Prevent duplicate submit
        Swal.fire({
            title: "កំពុងដំណើរការ...",
            text: "សូមរង់ចាំបន្តិច",
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await axios.post("/evaluations", formData);

        await Swal.fire({
            icon: "success",
            title: "ជោគជ័យ",
            text: response.data.message,
            confirmButtonText: "យល់ព្រម",
            confirmButtonColor: "#2563eb"
        });

        if (response.data.redirect_url) {
            window.location.href = response.data.redirect_url;
        }

    } catch (error) {

        console.error(error);

        let message = "មានបញ្ហាក្នុងការបញ្ជូនការវាយតម្លៃ។";

        // Laravel validation error
        if (error.response?.status === 422) {

            const errors = error.response.data.errors;

            if (errors) {
                message = Object.values(errors)
                    .flat()
                    .join("\n");
            }

        }

        // Server error
        if (error.response?.status === 500) {
            message = "មានបញ្ហាពីម៉ាស៊ីនមេ។ សូមព្យាយាមម្តងទៀត។";
        }

        await Swal.fire({
            icon: "error",
            title: "បរាជ័យ",
            text: message,
            confirmButtonText: "យល់ព្រម",
            confirmButtonColor: "#dc2626"
        });

    }

}