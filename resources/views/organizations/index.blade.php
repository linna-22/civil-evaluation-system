@extends('layouts.app')

@section('title', 'Organizations')

@section('content')

    <div class="space-y-6">



        {{-- Page Header --}}
        <x-page-header title="អង្គភាព" description="គ្រប់គ្រងព័ត៌មានអង្គភាព">
            <x-slot:actions>
                <div class="flex items-center gap-3">

                    {{-- Import Excel Button --}}
                    <button type="button" id="open-organization-import"
                        class="inline-flex h-11 items-center justify-center gap-2
                       rounded-xl border border-emerald-200 bg-white
                       px-4 text-sm font-semibold text-emerald-700
                       shadow-sm transition duration-200
                       hover:border-emerald-300 hover:bg-emerald-50
                       focus:outline-none focus:ring-2
                       focus:ring-emerald-500 focus:ring-offset-2 cursor-pointer">
                        <i data-lucide="file-spreadsheet" class="h-5 w-5 text-emerald-600"></i>
                        <span>នាំចូលទិន្នន័យអង្គភាពជា Excel</span>
                    </button>

                    {{-- Create Organization --}}
                    <x-action-btn href="{{ route('organizations.create') }}" icon="plus">
                        បន្ថែមអង្គភាព
                    </x-action-btn>

                </div>
            </x-slot:actions>
        </x-page-header>


        {{-- Organization Import Modal --}}
        <div id="organization-import-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center
           bg-gray-900/50 p-4 backdrop-blur-sm"
            role="dialog" aria-modal="true" aria-labelledby="organization-import-title">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl
                bg-white shadow-2xl">

                {{-- Modal Header --}}
                <div class="flex items-start justify-between border-b
                    border-gray-100 px-6 py-5">

                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center
                            rounded-xl bg-emerald-50 text-emerald-600">
                            <i data-lucide="file-spreadsheet" class="h-5 w-5 text-emerald-600"></i>
                        </div>

                        <div>
                            <h2 id="organization-import-title" class="text-lg font-bold text-gray-900">
                                នាំចូលទិន្នន័យអង្គភាព
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                ជ្រើសរើសឯកសារ Excel របស់អ្នក
                            </p>
                        </div>
                    </div>

                    <button type="button" id="close-organization-import"
                        class="flex h-9 w-9 items-center justify-center
                       rounded-lg text-gray-400 transition
                       hover:bg-gray-100 hover:text-gray-700"
                        aria-label="Close modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form id="organization-import-form" action="{{ route('organizations.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-5 px-6 py-6">

                        <label for="organization-excel-file"
                            class="flex cursor-pointer flex-col items-center
                           justify-center rounded-xl border-2
                           border-dashed border-gray-200 bg-gray-50
                           px-5 py-8 text-center transition
                           hover:border-emerald-400 hover:bg-emerald-50/50">

                            <div
                                class="mb-3 flex h-14 w-14 items-center justify-center
                                    rounded-full bg-emerald-100 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                    class="h-7 w-7" aria-hidden="true">
                                    <path d="M12 16V4" />
                                    <path d="m7 9 5-5 5 5" />
                                    <path d="M20 16.5v2A1.5 1.5 0 0 1 18.5 20h-13A1.5 1.5 0 0 1 4 18.5v-2" />
                                </svg>
                            </div>


                            <span class="text-sm font-semibold text-gray-800">
                                ចុចទីនេះដើម្បីជ្រើសរើសឯកសារ
                            </span>

                            <span class="mt-2 text-xs text-gray-500">
                                Excel (.xlsx, .xls) ឬ CSV · អតិបរមា 10 MB
                            </span>

                            <input type="file" id="organization-excel-file" name="file" accept=".xlsx,.xls,.csv"
                                required class="sr-only">
                        </label>

                        {{-- Selected File --}}
                        <div id="organization-selected-file"
                            class="hidden items-center gap-3 rounded-xl
                           border border-emerald-100 bg-emerald-50 p-3">
                            <i data-lucide="file-spreadsheet" class="h-5 w-5 text-emerald-600"></i>
                            <div class="min-w-0 flex-1">
                                <p id="organization-file-name" class="truncate text-sm font-semibold text-gray-800">
                                </p>
                                <p class="text-xs text-gray-500">
                                    ឯកសារដែលបានជ្រើសរើស
                                </p>
                            </div>

                            <button type="button" id="remove-organization-file"
                                class="flex h-8 w-8 items-center justify-center
                               rounded-lg text-gray-400 hover:bg-white
                               hover:text-red-600"
                                aria-label="Remove selected file">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>

                        <div class="rounded-lg bg-blue-50 p-3 text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            សូមពិនិត្យលេខកូដអង្គភាព និងឈ្មោះអង្គភាពឱ្យបានត្រឹមត្រូវ
                            មុនពេលនាំចូល។
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div
                        class="flex justify-end gap-3 border-t border-gray-100
                        bg-gray-50 px-6 py-4">

                        <button type="button" id="cancel-organization-import"
                            class="rounded-lg border border-gray-200 bg-white
                           px-4 py-2.5 text-sm font-medium text-gray-700
                           transition hover:bg-gray-100">
                            បោះបង់
                        </button>

                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2
                           rounded-lg bg-emerald-600 px-5 py-2.5
                           text-sm font-semibold text-white shadow-sm
                           transition hover:bg-emerald-700
                           focus:outline-none focus:ring-2
                           focus:ring-emerald-500 focus:ring-offset-2 cursor-pointer">
                            <i data-lucide="file-spreadsheet" class="h-5 w-5 text-white"></i>
                            នាំចូលទិន្នន័យ
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Import Success Message --}}
        {{-- @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-green-800">
                <div class="flex items-start gap-2">
                    <i class="fas fa-check-circle mt-1"></i>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif --}}

        {{-- Import Validation Errors --}}
        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-red-800">
                <div class="mb-2 flex items-center gap-2 font-semibold">
                    <i class="fas fa-exclamation-circle"></i>
                    សូមពិនិត្យទិន្នន័យ Excel ម្តងទៀត
                </div>

                <ul class="list-inside list-disc space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif




        {{-- Search Card --}}
        <div class="bg-white rounded-2xl shadow-sm p-3">
            <div class="flex justify-between items-center">
                <x-search-box id="organization-search" />
                <x-per-page id="organization-per-page" />
            </div>
        </div>


        {{-- Table --}}
        <div class="bg-white rounded-2xl">

            <div class="data-table-scroll">

                <x-data-table bodyId="organization-table-body">

                    <x-slot:head>

                        <th class="px-6 py-3 text-left w-20">
                            #
                        </th>

                        <th class="px-6 py-3 text-left">
                            លេខកូដ
                        </th>

                        <th class="px-6 py-3 text-left">
                            ឈ្មោះអង្គភាព (ភាសាខ្មែរ)
                        </th>

                        <th class="px-6 py-3 text-left">
                            ឈ្មោះអង្គភាព (ភាសាអង់គ្លេស)
                        </th>

                        <th class="px-6 py-3 text-left">
                            ស្ថានភាព
                        </th>

                        <th class="px-6 py-3 text-center w-40">
                            សកម្មភាព
                        </th>

                    </x-slot:head>

                    <x-slot:body>

                        <tbody id="organization-table-body">
                        </tbody>

                    </x-slot:body>

                </x-data-table>

            </div>

        </div>


        {{-- Pagination --}}
        <div id="organization-pagination" class="mt-6">
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('organization-import-modal');
            const openButton = document.getElementById('open-organization-import');
            const closeButton = document.getElementById('close-organization-import');
            const cancelButton = document.getElementById('cancel-organization-import');
            const fileInput = document.getElementById('organization-excel-file');
            const selectedFile = document.getElementById('organization-selected-file');
            const fileName = document.getElementById('organization-file-name');
            const removeButton = document.getElementById('remove-organization-file');

            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function clearFile() {
                fileInput.value = '';
                fileName.textContent = '';
                selectedFile.classList.add('hidden');
                selectedFile.classList.remove('flex');
            }

            openButton.addEventListener('click', openModal);
            closeButton.addEventListener('click', closeModal);
            cancelButton.addEventListener('click', closeModal);

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            fileInput.addEventListener('change', function() {
                const file = fileInput.files[0];

                if (!file) {
                    clearFile();
                    return;
                }

                fileName.textContent = file.name;
                selectedFile.classList.remove('hidden');
                selectedFile.classList.add('flex');
            });

            removeButton.addEventListener('click', clearFile);
        });
    </script>

@endsection
