document.addEventListener("DOMContentLoaded", () => {

    const users =
        window.workPerformanceUsers || [];

    const evaluationUsers =
        document.getElementById("evaluationUsers");

    const currentUserName =
        document.getElementById("currentUserName");

    const currentUserPosition =
        document.getElementById("currentUserPosition");

    const currentPosition =
        document.getElementById("currentPosition");


    if (!users.length || !evaluationUsers) {
        return;
    }


    // =====================================================
    // Render Progress
    // =====================================================

    function renderProgress(currentIndex = 0) {

        const currentUser = users[currentIndex];
        currentUserName.textContent = currentUser.name_kh || "-";
        currentUserPosition.textContent = currentUser.position || "-";
        currentPosition.textContent = `មន្ត្រីទី${currentIndex + 1} នៃមន្ត្រីសរុប ${users.length}នាក់`;

        // =================================================
        // Clear Progress
        // =================================================
        evaluationUsers.innerHTML = "";
        // =================================================
        // Render Users
        // =================================================
        users.forEach((user, index) => {
            const isCurrent = index === currentIndex;
            const isCompleted = index < currentIndex;
            const isActive = isCurrent || isCompleted;
            // Step Container
            const step = document.createElement("div");
            step.className = `
                flex
                items-center
                flex-1
                min-w-0
            `;
            // Number + Name
            const content = document.createElement("div");
            content.className = `
                flex
                items-center
                gap-3
                shrink-0
            `;

            // Number Circle
            const number = document.createElement("div");
            number.className = `
                w-12
                h-12
                rounded-full
                flex
                items-center
                justify-center
                text-lg
                font-semibold
                transition-all
                ${isActive
                    ? "bg-blue-600 text-white"
                    : "bg-gray-200 text-gray-500"
                }
            `;
            number.textContent = index + 1;
            // User Name

            const name = document.createElement("span");
            name.className = `
                text-base
                font-medium
                whitespace-nowrap
                ${isActive
                    ? "text-blue-600"
                    : "text-gray-400"
                }
            `;

            name.textContent = user.name_kh || "-";
            content.appendChild(number);
            content.appendChild(name);
            step.appendChild(content);
            // Connector Line
            if (index < users.length - 1) {

                const line = document.createElement("div");
                line.className = `
                    h-1
                    flex-1
                    mx-5
                    rounded-full
                    transition-all
                    ${index < currentIndex
                        ? "bg-blue-600"
                        : "bg-gray-200"
                    }
                `;

                step.appendChild(line);

            }
            evaluationUsers.appendChild(step);
        });
    }

    // Public API
    window.workPerformanceProgress = {
        render: renderProgress
    };
});