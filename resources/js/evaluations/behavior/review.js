document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('behaviorEditModal');
    const backdrop = document.getElementById('behaviorEditBackdrop');
    const closeButton = document.getElementById('behaviorEditClose');
    const cancelButton = document.getElementById('behaviorEditCancel');
    const form = document.getElementById('behaviorEditForm');
    const evaluatorName = document.getElementById('behaviorEditEvaluator');
    const totalElement = document.getElementById('behaviorEditTotal');
    const submitButton = document.getElementById('behaviorEditSubmit');
    const successModal = document.getElementById('behaviorEditSuccessModal');
    const successContinue = document.getElementById('behaviorEditSuccessContinue');

    if (!modal || !form) return;

    const fields = [
        'discipline',
        'responsibility',
        'professional_ethics',
        'work_performance',
        'self_development',
        'initiative_creativity',
        'teamwork',
        'interpersonal_skill',
        'work_under_pressure',
        'leadership',
    ];

    let activeButton = null;
    let redirectTimer = null;

    function csrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function calculateTotal() {
        let total = 0;

        fields.forEach(function (field) {
            const checked = form.querySelector('input[name="' + field + '"]:checked');
            if (checked) total += Number(checked.value);
        });

        totalElement.textContent = total;
        return total;
    }

    function setFieldValue(field, value) {
        const input = form.querySelector(
            'input[name="' + field + '"][value="' + value + '"]'
        );

        if (input) input.checked = true;
    }

    function getReviewUrl() {
        return document.querySelector('[data-behavior-review-url]')?.dataset.behaviorReviewUrl
            || window.location.href;
    }

    function showSuccessModal(message) {
        if (!successModal) {
            window.location.href = getReviewUrl();
            return;
        }

        // Clear any previous timer
        if (redirectTimer) {
            clearTimeout(redirectTimer);
            redirectTimer = null;
        }

        const messageElement = document.getElementById('behaviorEditSuccessMessage');

        if (messageElement && message) {
            messageElement.textContent = message;
        }

        successModal.classList.remove('hidden');
        successModal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    function closeSuccessModal() {
        if (redirectTimer) {
            clearTimeout(redirectTimer);
            redirectTimer = null;
        }

        window.location.href = getReviewUrl();
    }

    function openModal(button) {
        activeButton = button;

        evaluatorName.textContent = button.dataset.evaluatorName || 'មិនមានឈ្មោះ';
        form.action = button.dataset.updateUrl;

        fields.forEach(function (field) {
            const datasetKey = field.replace(/_([a-z])/g, function (_, letter) {
                return letter.toUpperCase();
            });

            setFieldValue(field, Number(button.dataset[datasetKey]) || 0);
        });

        calculateTotal();

        modal.classList.remove('hidden');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');

        requestAnimationFrame(function () {
            const firstChecked = form.querySelector('input[type="radio"]:checked');
            if (firstChecked) firstChecked.focus();
        });
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
        activeButton = null;
        form.reset();
        evaluatorName.textContent = '—';
        totalElement.textContent = '0';
        submitButton.disabled = false;
        submitButton.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> រក្សាទុកការកែប្រែ';

        if (window.lucide) window.lucide.createIcons();
    }

    document.querySelectorAll('.behavior-edit-btn').forEach(function (button) {
        button.addEventListener('click', function () {
            openModal(button);
        });
    });

    form.querySelectorAll('.behavior-score-input').forEach(function (input) {
        input.addEventListener('change', calculateTotal);
    });

    closeButton?.addEventListener('click', closeModal);
    cancelButton?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);
    successContinue?.addEventListener('click', closeSuccessModal);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const total = calculateTotal();
        const allSelected = fields.every(function (field) {
            return !!form.querySelector('input[name="' + field + '"]:checked');
        });

        if (!allSelected || total < 0 || total > 20) {
            alert('សូមជ្រើសរើសពិន្ទុសម្រាប់លក្ខណៈវាយតម្លៃទាំងអស់។');
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = '<i data-lucide="loader-circle" class="w-4 h-4 animate-spin"></i> កំពុងរក្សាទុក...';
        if (window.lucide) window.lucide.createIcons();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: new FormData(form),
            });

            const data = await response.json().catch(function () {
                return {};
            });

            if (!response.ok) {
                const validationMessage = data?.errors
                    ? Object.values(data.errors).flat().join(' ')
                    : (data?.message || 'មិនអាចរក្សាទុកការកែប្រែបានទេ។');

                throw new Error(validationMessage);
            }

            const savedTotal = Number(data?.data?.total_score ?? total);
            const evaluationId = String(data?.data?.evaluation_id ?? activeButton?.dataset?.evaluationId ?? '');

            document
                .querySelectorAll('[data-evaluation-total="' + evaluationId + '"]')
                .forEach(function (element) {
                    if (element.classList.contains('behavior-card-total')) {
                        if (element.tagName.toLowerCase() === 'p') {
                            element.textContent = savedTotal + '/20';
                        } else {
                            element.textContent = savedTotal;
                        }
                    }
                });

            // Keep the evaluator button's data attributes synchronized in case
            // the modal is opened again without a page reload.
            if (activeButton) {
                fields.forEach(function (field) {
                    const checked = form.querySelector('input[name="' + field + '"]:checked');
                    if (checked) {
                        const datasetKey = field.replace(/_([a-z])/g, function (_, letter) {
                            return letter.toUpperCase();
                        });
                        activeButton.dataset[datasetKey] = checked.value;
                    }
                });
            }

            closeModal();
            showSuccessModal(
                'ពិន្ទុត្រូវបានកែប្រែ ហើយពិន្ទុសរុបរបស់មន្ត្រីត្រូវបានគណនាឡើងវិញ។'
            );
        } catch (error) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i data-lucide="save" class="w-4 h-4"></i> រក្សាទុកការកែប្រែ';
            if (window.lucide) window.lucide.createIcons();

            alert(error.message || 'មានបញ្ហាក្នុងការរក្សាទុកការកែប្រែ។');
        }
    });
});
