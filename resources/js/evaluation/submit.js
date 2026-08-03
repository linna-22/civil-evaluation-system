import axios from "axios";
import Swal from "sweetalert2";

export function submitEvaluation() {

    const form = document.getElementById("evaluationForm");

    const formData = new FormData(form);

    axios.post("/evaluations", formData)

        .then((response) => {

            // console.log(response.data);

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

            console.log(error.response.data.errors);

            Swal.fire({
                icon: "error",
                title: "បរាជ័យ",
                text: "មានបញ្ហាក្នុងការបញ្ជូនការវាយតម្លៃ។",
                confirmButtonColor: "#dc2626"
            });

        });
    //          .catch((error) => {

    //     console.log("Axios Error:", error);

    //     console.log("Response:", error.response);

    //     console.log("Data:", error.response?.data);

    //     console.log("Status:", error.response?.status);

    // });

}