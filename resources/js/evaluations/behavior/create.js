import { refreshIcons } from "../../utils/lucide";
document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // State
    // ==========================================
     const form = document.getElementById('behaviorEvaluationForm');

    const peers = JSON.parse(form.dataset.peers || '[]');

    const previewUrl = form.dataset.previewUrl;

    let currentIndex = 0;


    // ==========================================
    // Restore Temporary Evaluation Data
    // ==========================================

    const storedEvaluationData =
        sessionStorage.getItem(
            'behaviorEvaluationData'
        );


    const savedEvaluationData =
        storedEvaluationData ?
            JSON.parse(storedEvaluationData) :
            null;


    const answers =
        savedEvaluationData?.answers || {};

    // ==========================================
    // Elements
    // ==========================================

    const progressSteps =
        document.getElementById('progressSteps');

    const currentPeerNumber =
        document.getElementById('currentPeerNumber');

    const totalPeers =
        document.getElementById('totalPeers');

    const peerNameKh =
        document.getElementById('peerNameKh');

    const peerNameEn =
        document.getElementById('peerNameEn');

    const peerPosition =
        document.getElementById('peerPosition');

    const peerAvatar =
        document.getElementById('peerAvatar');

    const sectionOne =
        document.getElementById('sectionOne');

    const sectionTwo =
        document.getElementById('sectionTwo');

    const sectionThree =
        document.getElementById('sectionThree');

    const previousButton =
        document.getElementById('previousButton');

    const nextButton =
        document.getElementById('nextButton');


    // ==========================================
    // Criteria
    // ==========================================

    const criteria = {

        sectionOne: [

            {
                key: 'discipline',
                label: 'គោរពវិន័យការងារ ម៉ោងពេលធ្វើការ និងបទបញ្ជាផ្ទៃក្នុងរបស់អង្គភាព'
            },

            {
                key: 'responsibility',
                label: 'ស្មារតីទទួលខុសត្រូវ'
            },

            {
                key: 'professional_ethics',
                label: 'ការគោរពឋានានុក្រមការងារ និងគោរពការសម្ងាត់វិជ្ជាជីវៈ និងកាតព្វកិច្ចលក្ខណការណ៍'
            }

        ],


        sectionTwo: [

            {
                key: 'work_performance',
                label: 'សមត្ថភាពបំពេញការងារ'
            },

            {
                key: 'self_development',
                label: 'ឆន្ទៈក្នុងការអភិវឌ្ឍសមត្ថភាព ចំណេះដឹង និងជំនាញ'
            },

            {
                key: 'initiative_creativity',
                label: 'មានគំនិតផ្តួចផ្តើម និងច្នៃប្រឌិត'
            }

        ],


        sectionThree: [

            {
                key: 'teamwork',
                label: 'សហការជាមួយមន្ត្រីរាជការដទៃដើម្បីសម្រេចលទ្ធផលរួម / ស្មារតីជាក្រុម'
            },

            {
                key: 'interpersonal_skill',
                label: 'ទំនាក់ទំនងអន្តរបុគ្គល'
            },

            {
                key: 'work_under_pressure',
                label: 'សមត្ថភាពអនុវត្តការងារក្រោមសម្ពាធ'
            },

            {
                key: 'leadership',
                label: 'សមត្ថភាពភាពជាអ្នកដឹកនាំ'
            }

        ]

    };


    // ==========================================
    // Render Progress
    // ==========================================

    function renderProgress() {

        progressSteps.innerHTML = '';

        peers.forEach((peer, index) => {

            const isCurrent =
                index === currentIndex;

            const isCompleted =
                index < currentIndex;


            const wrapper =
                document.createElement('div');

            wrapper.className = `
                flex
                items-center
                ${index < peers.length - 1
                    ? 'flex-1'
                    : ''
                }
            `;


            const circle =
                document.createElement('div');

            circle.className = `
                w-11
                h-11
                rounded-full
                flex
                items-center
                justify-center
                font-semibold
                text-sm
                shrink-0
                ${isCompleted
                    ? 'bg-green-600 text-white'
                    : isCurrent
                        ? 'bg-blue-600 text-white'
                        : 'bg-gray-200 text-gray-500'
                }
            `;

            circle.textContent =
                index + 1;


            const name =
                document.createElement('span');

            name.className = `
                hidden
                md:block
                ml-3
                text-sm
                ${isCurrent
                    ? 'text-blue-600 font-medium'
                    : isCompleted
                        ? 'text-green-600'
                        : 'text-gray-400'
                }
            `;

            name.textContent =
                peer.name_kh;


            const content =
                document.createElement('div');

            content.className =
                'flex items-center';


            content.appendChild(circle);
            content.appendChild(name);

            wrapper.appendChild(content);


            if (index < peers.length - 1) {

                const line =
                    document.createElement('div');

                line.className = `
                    flex-1
                    h-1
                    mx-4
                    rounded
                    ${index < currentIndex
                        ? 'bg-green-600'
                        : 'bg-gray-200'
                    }
                `;

                wrapper.appendChild(line);

            }


            progressSteps.appendChild(wrapper);

        });

    }


    // ==========================================
    // Render Score Options
    // ==========================================

    function renderCriteria(
        container,
        section,
        peerId
    ) {

        container.innerHTML = '';


        criteria[section].forEach(
            criterion => {

                const savedValue =
                    answers[peerId]?.[
                    criterion.key
                    ] ?? null;


                const row =
                    document.createElement('div');

                row.className = `
                    px-6
                    py-5
                    border-b
                    border-gray-100
                    last:border-b-0
                `;


                row.innerHTML = `

                    <div class="
                        flex
                        flex-col
                        lg:flex-row
                        lg:items-center
                        lg:justify-between
                        gap-4
                    ">

                        <div class="flex-1">

                            <p class="
                                text-sm
                                text-gray-700
                                leading-7
                            ">
                                ${criterion.label}
                            </p>

                        </div>


                        <div class="
                            flex
                            items-center
                            gap-6
                            shrink-0
                        ">

                            ${[0, 1, 2].map(score => `

                                            <label
                                                class="
                                                    flex
                                                    items-center
                                                    gap-2
                                                    cursor-pointer
                                                    text-gray-700
                                                "
                                            >

                                                <input
                                                    type="radio"
                                                    name="${criterion.key}"
                                                    value="${score}"
                                                    class="
                                                        w-4
                                                        h-4
                                                        text-blue-600
                                                        border-gray-300
                                                        focus:ring-blue-500
                                                    "
                                                    ${savedValue !== null &&
                        Number(savedValue) === score
                        ? 'checked'
                        : ''
                    }
                                                >

                                                <span class="text-sm">
                                                    ${score}
                                                </span>

                                            </label>

                                        `).join('')}

                        </div>

                    </div>

                `;


                container.appendChild(row);

            }
        );

    }


    // ==========================================
    // Load Peer
    // ==========================================

    function loadPeer(index) {

        currentIndex = index;

        const peer =
            peers[currentIndex];

        if (!peer) {
            return;
        }


        currentPeerNumber.textContent =
            currentIndex + 1;

        totalPeers.textContent =
            peers.length;


        peerNameKh.textContent =
            peer.name_kh ?? '';

        peerNameEn.textContent =
            peer.name_en ?? '';

        peerPosition.textContent =
            peer.position ?? '';

        peerAvatar.textContent =
            peer.name_kh ?
                peer.name_kh.substring(0, 1) :
                '';


        if (!answers[peer.user_id]) {

            answers[peer.user_id] = {};

        }


        renderCriteria(
            sectionOne,
            'sectionOne',
            peer.user_id
        );

        renderCriteria(
            sectionTwo,
            'sectionTwo',
            peer.user_id
        );

        renderCriteria(
            sectionThree,
            'sectionThree',
            peer.user_id
        );


        renderProgress();


        previousButton.disabled =
            currentIndex === 0;

        previousButton.classList.toggle(
            'opacity-50',
            currentIndex === 0
        );

        previousButton.classList.toggle(
            'cursor-not-allowed',
            currentIndex === 0
        );


        if (
            currentIndex ===
            peers.length - 1
        ) {

            nextButton.innerHTML = `
                ពិនិត្យលទ្ធផលវាយតម្លៃ

                <i
                    data-lucide="eye"
                    class="w-4 h-4 cursor-pointer"
                ></i>
            `;

        } else {

            nextButton.innerHTML = `
                បន្ទាប់

                <i
                    data-lucide="arrow-right"
                    class="w-4 h-4 cursor-pointer"
                ></i>
            `;

        }


        if (
            typeof refreshIcons === 'function'
        ) {

            refreshIcons();

        }

    }


    // ==========================================
    // Save Current Answers
    // ==========================================

    function saveCurrentAnswers() {

        const peer =
            peers[currentIndex];

        if (!peer) {
            return;
        }


        const peerId =
            peer.user_id;


        const inputs =
            document.querySelectorAll(
                'input[type="radio"]'
            );


        inputs.forEach(input => {

            if (input.checked) {

                answers[peerId][input.name] =
                    Number(input.value);

            }

        });

    }


    // ==========================================
    // Validate Current Peer
    // ==========================================

    function validateCurrentPeer() {

        const allCriteria = [

            ...criteria.sectionOne,
            ...criteria.sectionTwo,
            ...criteria.sectionThree

        ];


        const peer =
            peers[currentIndex];


        for (const criterion of allCriteria) {

            if (
                answers[peer.user_id]?.[
                criterion.key
                ] === undefined
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'សូមបំពេញការវាយតម្លៃ',

                    text: 'សូមជ្រើសរើសពិន្ទុសម្រាប់គ្រប់លក្ខណៈវិនិច្ឆ័យជាមុនសិន។',

                    confirmButtonText: 'យល់ព្រម'

                });

                return false;

            }

        }


        return true;

    }


    // ==========================================
    // Radio Change
    // ==========================================

    document.addEventListener(
        'change',
        function (event) {

            if (
                event.target.matches(
                    'input[type="radio"]'
                )
            ) {

                const peer =
                    peers[currentIndex];

                if (!peer) {
                    return;
                }


                if (
                    !answers[peer.user_id]
                ) {

                    answers[peer.user_id] = {};

                }


                answers[
                    peer.user_id
                ][
                    event.target.name
                ] =
                    Number(
                        event.target.value
                    );

            }

        }
    );


    // ==========================================
    // Previous
    // ==========================================

    previousButton.addEventListener(
        'click',
        function () {

            if (currentIndex === 0) {
                return;
            }


            saveCurrentAnswers();

            loadPeer(
                currentIndex - 1
            );

        }
    );


    // ==========================================
    // Next / Preview
    // ==========================================

    nextButton.addEventListener(
        'click',
        function () {

            saveCurrentAnswers();


            if (!validateCurrentPeer()) {
                return;
            }


            // ======================================
            // Go Next
            // ======================================

            if (
                currentIndex <
                peers.length - 1
            ) {

                loadPeer(
                    currentIndex + 1
                );

                return;

            }


            // ======================================
            // Last Peer → Preview
            // ======================================

            showPreview();

        }
    );


    // ==========================================
    // Preview
    // ==========================================

    // ==========================================
    // Show Preview
    // ==========================================

    function showPreview() {

        saveCurrentAnswers();

        const evaluationData = {
            peers: peers,
            answers: answers
        };

        sessionStorage.setItem(
            'behaviorEvaluationData',
            JSON.stringify(evaluationData)
        );

        window.location.href = previewUrl;
    }

    // ==========================================
    // Start
    // ==========================================
    if (!peers.length) {
        Swal.fire({
            icon: 'info',
            title: 'មិនមានមន្ត្រី',
            text: 'មិនមានមន្ត្រីដែលត្រូវវាយតម្លៃទេ។',
            confirmButtonText: 'យល់ព្រម'
        });

        return;
    }
    loadPeer(0);
});