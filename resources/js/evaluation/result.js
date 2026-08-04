export function loadResult() {

    const work = getScore("previewWorkPerformanceScore");

    const attendance = getScore("previewAttendanceScore");

    const behavior = getScore("previewBehaviorScore");

    const total = work + attendance + behavior;

    document.getElementById("resultWorkScore").textContent =
        `${work} / 60`;

    document.getElementById("resultAttendanceScore").textContent =
        `${attendance} / 20`;

    document.getElementById("resultBehaviorScore").textContent =
        `${behavior} / 20`;

    document.getElementById("resultTotalScore").textContent =
        total.toFixed(2).replace(/\.00$/, "");

    const rating = getRating(total);

    document.getElementById("resultRatingBadge").textContent =
        rating.title;

    document.getElementById("resultRatingTitle").textContent =
        rating.title;

    document.getElementById("resultRatingDescription").textContent =
        rating.description;

    updateRatingColor(total);

}

function getScore(id) {

    return parseFloat(
        document
            .getElementById(id)
            .textContent
            .split("/")[0]
            .trim()
    ) || 0;

}

function getRating(score) {

    if (score >= 90) {

        return {

            title: "ល្អណាស់",

            description:
                "មន្ត្រីរាជការបានបំពេញការងារបានយ៉ាងមានប្រសិទ្ធភាព មានការទទួលខុសត្រូវខ្ពស់ និងសម្រេចគោលដៅការងារបានយ៉ាងល្អប្រសើរ។"

        };

    }

    if (score >= 80) {

        return {

            title: "ល្អ",

            description:
                "មន្ត្រីរាជការបានបំពេញការងារបានល្អ និងសម្រេចគោលដៅការងារបានភាគច្រើន។"

        };

    }

    if (score >= 70) {

        return {

            title: "ល្អបង្គួរ",

            description:
                "មន្ត្រីរាជការបានបំពេញការងារបានត្រឹមត្រូវ ប៉ុន្តែនៅមានចំណុចដែលត្រូវកែលម្អ។"

        };

    }

    if (score >= 60) {

        return {

            title: "មធ្យម",

            description:
                "មន្ត្រីរាជការគួរបង្កើនប្រសិទ្ធភាព និងការទទួលខុសត្រូវក្នុងការងារ។"

        };

    }

    return {

        title: "ត្រូវកែលម្អ",

        description:
            "មន្ត្រីរាជការត្រូវកែលម្អការបំពេញការងារ និងអនុវត្តផែនការអភិវឌ្ឍន៍បន្ថែម។"

    };

}

function updateRatingColor(score) {

    const card = document.getElementById("resultRatingCard");

    card.className =
        "mt-8 rounded-xl p-6 border";

    if (score >= 90) {

        card.classList.add(
            "border-green-200",
            "bg-green-50"
        );

    } else if (score >= 80) {

        card.classList.add(
            "border-blue-200",
            "bg-blue-50"
        );

    } else if (score >= 70) {

        card.classList.add(
            "border-yellow-200",
            "bg-yellow-50"
        );

    } else {

        card.classList.add(
            "border-red-200",
            "bg-red-50"
        );

    }

}