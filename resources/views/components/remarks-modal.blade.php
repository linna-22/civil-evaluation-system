<div
    id="remarks-modal"
    class="fixed inset-0 z-50 hidden"
    aria-hidden="true"
>
    {{-- Backdrop --}}
    <div
        id="remarks-modal-backdrop"
        class="absolute inset-0 bg-black/40 backdrop-blur-sm"
    ></div>

    {{-- Modal --}}
    <div class="relative flex min-h-full items-center justify-center p-4">

        <div
            class="w-full max-w-lg rounded-2xl bg-white shadow-xl"
            role="dialog"
            aria-modal="true"
            aria-labelledby="remarks-modal-title"
        >

            {{-- Header --}}
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">

                <div>
                    <h2
                        id="remarks-modal-title"
                        class="text-lg font-semibold text-gray-800"
                    >
                        បន្ថែមមូលវិចារណ៍
                    </h2>

                    <p
                        id="remarks-modal-employee"
                        class="mt-1 text-sm text-gray-500"
                    >
                        មន្ត្រី
                    </p>
                </div>

                <button
                    type="button"
                    id="remarks-modal-close"
                    class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition"
                >
                    ✕
                </button>

            </div>

            {{-- Body --}}
            <div class="px-6 py-5">

                <label
                    for="remarks-input"
                    class="mb-2 block text-sm font-medium text-gray-700"
                >
                    មូលវិចារណ៍
                </label>

                <textarea
                    id="remarks-input"
                    rows="5"
                    maxlength="1000"
                    placeholder="សូមបញ្ចូលមូលវិចារណ៍..."
                    class="w-full resize-none rounded-xl border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                ></textarea>

                <p class="mt-1 text-xs text-gray-400">
                    មូលវិចារណ៍នេះមិនតម្រូវឱ្យបញ្ចូលទេ។
                </p>

                {{-- Hidden ID --}}
                <input
                    type="hidden"
                    id="remarks-evaluation-summary-id"
                >

            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 border-t border-gray-100 px-6 py-4">

                <button
                    type="button"
                    id="remarks-modal-cancel"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 transition"
                >
                    បោះបង់
                </button>

                <button
                    type="button"
                    id="remarks-modal-save"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition"
                >
                    រក្សាទុក
                </button>

            </div>

        </div>
    </div>
</div>