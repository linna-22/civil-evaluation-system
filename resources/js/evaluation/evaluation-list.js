import { refreshIcons } from "../utils/lucide";

let form;
let tableBody;
let pagination;

document.addEventListener("DOMContentLoaded", () => {

    form = document.getElementById("evaluationFilterForm");
    tableBody = document.getElementById("tableBody");
    pagination = document.getElementById("pagination");

    form.addEventListener("submit", function (e) {
        e.preventDefault();
    });

    initPagination();
    initSearch();
    initFilters();
    initReset();
    initReportPreview();

});

async function loadEvaluations(url = null) {
    try {

        let requestUrl;

        if (url) {

            requestUrl = url;

        } else {

            const params = new URLSearchParams(
                new FormData(form)
            );

            requestUrl = `${form.action}?${params.toString()}`;

        }

        const response = await fetch(requestUrl, {

            headers: {

                "X-Requested-With": "XMLHttpRequest",

            }

        });

        const data = await response.json();

        tableBody.innerHTML = data.tbody;

        pagination.innerHTML = data.pagination;

        refreshIcons();

    } catch (error) {

        console.error(error);

    }

}
function initPagination() {

    document.addEventListener("click", function (e) {

        const link = e.target.closest("#pagination a");

        if (!link) return;

        e.preventDefault();
        console.log("Pagination Click");

        loadEvaluations(link.href);

    });

}

function debounce(callback, delay = 300) {

    let timeout;

    return (...args) => {

        clearTimeout(timeout);

        timeout = setTimeout(() => {

            callback(...args);

        }, delay);

    };

}
function initSearch() {

    const searchInput = form.querySelector(
        'input[name="search"]'
    );

    if (!searchInput) return;

    searchInput.addEventListener(
        "input",
        debounce(() => {

            loadEvaluations();

        }, 300)
    );

}
function initFilters() {

    const filterNames = [
        "organization",
        "department",
        "month",
        "year",
    ];

    filterNames.forEach((name) => {

        const element = form.querySelector(`[name="${name}"]`);

        if (!element) return;

        element.addEventListener("change", () => {

            loadEvaluations();

        });

    });

}
//Reset Filter
function initReset() {

    const button = document.getElementById("resetFilter");
    if (!button) return;
    button.addEventListener("click", () => {
        form.reset();
        ["organization", "department", "month", "year"].forEach(id => {
            const select = document.getElementById(id);
            if (select?.tomselect) {
                select.tomselect.setValue(select.value, true);
            }
        });
        loadEvaluations();
    });
}
// Report Preview
function initReportPreview() {

    const previewBtn = document.getElementById("previewReport");

    if (!previewBtn) return;

    previewBtn.addEventListener("click", function (e) {

        e.preventDefault();

        const params = new URLSearchParams(
            new FormData(form)
        );

        window.open(
            `/reports/evaluation/preview?${params.toString()}`,
            "_blank"
        );

    });

}