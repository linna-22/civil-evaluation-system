import Swal from "sweetalert2";
import { validateWorkPerformance } from "./validation";
import { refreshIcons } from "../utils/lucide";
import { submitEvaluation } from "./submit";
// ========================================
// Evaluation Wizard
// ========================================

let currentStep = 1;

const totalSteps = 5;

const steps = document.querySelectorAll(".wizard-step");

const nextBtn = document.getElementById("nextBtn");

const previousBtn = document.getElementById("previousBtn");

// ========================================
// Initialize
// ========================================

document.addEventListener("DOMContentLoaded", () => {

    showStep(currentStep);

});

// ========================================
// Show Current Step
// ========================================

function showStep(step) {

    // Hide all steps
    steps.forEach((item) => {

        item.classList.add("hidden");

    });

    // Show selected step
    document
        .querySelector(`[data-step="${step}"]`)
        .classList
        .remove("hidden");

    updateButtons(step);

    updateProgress(step);

}

// ========================================
// Previous / Next Buttons
// ========================================

// function updateButtons(step) {

//     previousBtn.disabled = step === 1;

//     previousBtn.classList.toggle(
//         "opacity-50",
//         step === 1
//     );

//     previousBtn.classList.toggle(
//         "cursor-not-allowed",
//         step === 1
//     );

//     if (step === totalSteps) {

//         nextBtn.innerHTML = `
//             <i data-lucide="check" class="w-5 h-5 cursor-pointer"></i>
//             ដាក់ស្នើ
//         `;

//     } else {

//         nextBtn.innerHTML = `
//             បន្ទាប់
//             <i data-lucide="arrow-right" class="w-5 h-5 cursor-pointer"></i>
//         `;

//     }

//     refreshIcons();

// }
function updateButtons() {

    previousBtn.disabled = currentStep === 1;

    previousBtn.classList.toggle("opacity-50", currentStep === 1);
    previousBtn.classList.toggle("cursor-not-allowed", currentStep === 1);

    if (currentStep === totalSteps) {

        nextBtn.innerHTML = `
            <i data-lucide="check" class="w-5 h-5 cursor-pointer"></i>
            បញ្ជូនការវាយតម្លៃ
        `;

    } else if (currentStep === totalSteps - 1) {

        nextBtn.innerHTML = `
            មើលលទ្ធផលសរុប
            <i data-lucide="arrow-right" class="w-5 h-5 cursor-pointer"></i>
        `;

    } else {

        nextBtn.innerHTML = `
            បន្ទាប់
            <i data-lucide="arrow-right" class="w-5 h-5 cursor-pointer"></i>
        `;

    }

    refreshIcons();

}

// ========================================
// Next
// ========================================

nextBtn.addEventListener("click", () => {

    if (!validateStep(currentStep)) {

        return;

    }

    if (currentStep < totalSteps) {

        currentStep++;

        showStep(currentStep);

    } else {

         submitEvaluation();

    }

});

// ========================================
// Previous
// ========================================

previousBtn.addEventListener("click", () => {

    if (currentStep > 1) {

        currentStep--;

        showStep(currentStep);

    }

});

// ========================================
// Update Progress
// ========================================

function updateProgress(step) {

    const circles = document.querySelectorAll(".step-circle");
    const lines = document.querySelectorAll(".progress-line");
    const labels = document.querySelectorAll(".step-item span");

    circles.forEach((circle, index) => {

        circle.classList.remove(
            "active",
            "completed"
        );

        labels[index].classList.remove(
            "text-blue-600",
            "text-green-600",
            "font-semibold"
        );

        if (index + 1 < step) {

            circle.classList.add("completed");

            labels[index].classList.add(
                "text-green-600",
                "font-semibold"
            );

        }
        else if (index + 1 === step) {

            circle.classList.add("active");

            labels[index].classList.add(
                "text-blue-600",
                "font-semibold"
            );

        }

    });

    lines.forEach((line, index) => {

        line.classList.remove(
            "bg-gray-200",
            "bg-green-600"
        );

        if (index + 1 < step) {

            line.classList.add("bg-green-600");

        } else {

            line.classList.add("bg-gray-200");

        }

    });

}

// ========================================
// Validation
// ========================================

function validateStep(step) {

    switch (step) {

        case 1:

            // Validate Work Performance
            // return true;

            return validateWorkPerformance();

        case 2:

            // Validate Attendance
            return true;

        case 3:

            // Validate Behavior
            return true;

        case 4:
            // Preview

            return true;

        case 5:
            // result
            return true;

        default:

            return true;

    }

}

// ========================================
// Submit
// ========================================
// function submitEvaluation() {

//     Swal.fire({

//         title: "ដាក់បញ្ជូនការវាយតម្លៃ?",
//         text: "អ្នកនឹងមិនអាចកែប្រែបានទេ បន្ទាប់ពីដាក់បញ្ជូនរួច។",
//         icon: "question",
//         showCancelButton: true,
//         confirmButtonText: "ដាក់បញ្ជូន",
//         cancelButtonText: "បោះបង់",
//         confirmButtonColor: "#2563EB"

//     }).then((result) => {

//         if (result.isConfirmed) {

//             // TODO:
//             // Submit Form / AJAX
            

           

//         }

//     });

// }