import Swal from "sweetalert2";

export function validateWorkPerformance() {

    const rows = document.querySelectorAll("#performanceTableBody tr");

    if (rows.length === 0) {

        Swal.fire({
            icon: "warning",
            title: "មិនទាន់មានទិន្នន័យ",
            text: "សូមបន្ថែមសកម្មភាពការងារ។",
            confirmButtonColor: "#2563eb"
        });

        return false;

    }

    for (const row of rows) {

        const activity = row.querySelector("textarea[name*='activity']").value.trim();

        const indicator = row.querySelector("textarea[name*='indicator']").value.trim();

        const achievement = row.querySelector("input[name*='achievement_percent']").value;

        if (!activity) {

            Swal.fire({
                icon: "warning",
                title: "ព័ត៌មានមិនពេញលេញ",
                text: "សូមបំពេញសកម្មភាពការងារ។",
                confirmButtonColor: "#2563eb"
            });

            row.querySelector("textarea[name*='activity']").focus();

            return false;

        }

        if (!indicator) {

            Swal.fire({
                icon: "warning",
                title: "ព័ត៌មានមិនពេញលេញ",
                text: "សូមបំពេញសូចនាករសមិទ្ធកម្ម។",
                confirmButtonColor: "#2563eb"
            });

            row.querySelector("textarea[name*='indicator']").focus();

            return false;

        }

        if (achievement === "") {

            Swal.fire({
                icon: "warning",
                title: "ព័ត៌មានមិនពេញលេញ",
                text: "សូមបញ្ចូលលទ្ធផលសមិទ្ធកម្ម។",
                confirmButtonColor: "#2563eb"
            });

            row.querySelector("input[name*='achievement_percent']").focus();

            return false;

        }

        if (achievement < 0 || achievement > 100) {

            Swal.fire({
                icon: "warning",
                title: "ទិន្នន័យមិនត្រឹមត្រូវ",
                text: "លទ្ធផលសមិទ្ធកម្មត្រូវមានចន្លោះពី 0 ដល់ 100។",
                confirmButtonColor: "#2563eb"
            });

            row.querySelector("input[name*='achievement_percent']").focus();

            return false;

        }

    }

    return true;

}