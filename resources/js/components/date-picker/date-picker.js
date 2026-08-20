import flatpickr from "flatpickr";
import { Khmer } from "flatpickr/dist/l10n/km.js";

import "flatpickr/dist/flatpickr.min.css";


export default function DatePicker(
    selector,
    options = {}
) {

    const element = document.querySelector(selector);

    if (!element) {
        return null;
    }


    const defaultOptions = {

        // ==========================================
        // Date Format
        // ==========================================

        dateFormat: "Y-m-d",

        altInput: true,

        altFormat: "d/m/Y",


        // ==========================================
        // Khmer
        // ==========================================

        locale: {
            ...Khmer,

            weekdays: {

                shorthand: [
                    "អា",
                    "ច",
                    "អ",
                    "ព",
                    "ព្រ",
                    "សុ",
                    "ស"
                ],

                longhand: [
                    "អាទិត្យ",
                    "ចន្ទ",
                    "អង្គារ",
                    "ពុធ",
                    "ព្រហស្បតិ៍",
                    "សុក្រ",
                    "សៅរ៍"
                ]

            },

            months: {

                shorthand: [
                    "មករា",
                    "កុម្ភៈ",
                    "មីនា",
                    "មេសា",
                    "ឧសភា",
                    "មិថុនា",
                    "កក្កដា",
                    "សីហា",
                    "កញ្ញា",
                    "តុលា",
                    "វិច្ឆិកា",
                    "ធ្នូ"
                ],

                longhand: [
                    "មករា",
                    "កុម្ភៈ",
                    "មីនា",
                    "មេសា",
                    "ឧសភា",
                    "មិថុនា",
                    "កក្កដា",
                    "សីហា",
                    "កញ្ញា",
                    "តុលា",
                    "វិច្ឆិកា",
                    "ធ្នូ"
                ]

            },

            firstDayOfWeek: 1

        },


        // ==========================================
        // Calendar
        // ==========================================

        disableMobile: true,

        allowInput: false,


        // ==========================================
        // Custom Options
        // ==========================================

        ...options

    };


    return flatpickr(
        element,
        defaultOptions
    );
}