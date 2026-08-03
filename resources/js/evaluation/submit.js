import axios from "axios";
import Swal from "sweetalert2";

export function submitEvaluation() {

    const form = document.getElementById("evaluationForm");
    const formData = new FormData(form);

    Swal.fire({
        title: "បញ្ជាក់ការដាក់បញ្ជូន",
        text: "តើអ្នកពិតជាចង់ដាក់បញ្ជូនការវាយតម្លៃនេះមែនទេ? បន្ទាប់ពីដាក់បញ្ជូន អ្នកនឹងមិនអាចកែប្រែបានទៀតទេ។",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "បាទ/ចាស, ដាក់បញ្ជូន",
        cancelButtonText: "បោះបង់",
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#6b7280",
        reverseButtons: true,
    }).then((result) => {

        if (!result.isConfirmed) {
            return;
        }

        axios.post("/evaluations", formData)

            .then((response) => {

                Swal.fire({
                    icon: "success",
                    title: "ជោគជ័យ",
                    text: response.data.message,
                    confirmButtonText: "យល់ព្រម",
                    confirmButtonColor: "#2563eb"
                }).then(() => {

                    if (response.data.redirect_url) {
                        window.location.href = response.data.redirect_url;
                    }

                });

            })

            .catch((error) => {

                console.error(error);

                Swal.fire({
                    icon: "error",
                    title: "បរាជ័យ",
                    text: "មានបញ្ហាក្នុងការបញ្ជូនការវាយតម្លៃ។",
                    confirmButtonText: "យល់ព្រម",
                    confirmButtonColor: "#dc2626"
                });

            });

    });

}