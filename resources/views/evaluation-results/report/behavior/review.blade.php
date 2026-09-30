@extends('layouts.app')

@section('title', 'ពិនិត្យការវាយតម្លៃឥរិយាបថ')

@section('content')

    @php
        $employee = $user;

        $criteria = [
            'discipline' => 'វិន័យ',
            'responsibility' => 'ការទទួលខុសត្រូវ',
            'professional_ethics' => 'ក្រមសីលធម៌វិជ្ជាជីវៈ',
            'work_performance' => 'ប្រសិទ្ធភាពការងារ',
            'self_development' => 'ការអភិវឌ្ឍខ្លួន',
            'initiative_creativity' => 'គំនិតផ្តួចផ្តើម និងការច្នៃប្រឌិត',
            'teamwork' => 'ការងារជាក្រុម',
            'interpersonal_skill' => 'ទំនាក់ទំនងអន្តរបុគ្គល',
            'work_under_pressure' => 'ការងារក្រោមសម្ពាធ',
            'leadership' => 'ភាពជាអ្នកដឹកនាំ',
        ];
    @endphp

    <div class="min-h-screen bg-[#f5f8ff]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">
                        ពិនិត្យការវាយតម្លៃឥរិយាបថ
                    </h1>
                </div>

                <a
                    href="{{ route('evaluation-results.behavior.show', [
                        'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                    ]) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-gray-200 text-gray-700 text-sm font-semibold hover:bg-gray-50 transition"
                >
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    ត្រឡប់ក្រោយ
                </a>
            </div>

            {{-- Closed period notice --}}
            <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4">
                <div class="flex items-start gap-3">
                    <i data-lucide="triangle-alert" class="w-5 h-5 text-amber-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-amber-800">
                            ការវាយតម្លៃបានបិទហើយ
                        </p>
                        <p class="mt-1 text-xs text-amber-700">
                            ការកែប្រែពិន្ទុឥរិយាបថនឹងគណនាពិន្ទុវាយតម្លៃរបស់មន្ត្រីឡើងវិញដោយស្វ័យប្រវត្តិ។
                        </p>
                    </div>
                </div>
            </div>

            {{-- Employee information --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">
                                ព័ត៌មានមន្ត្រី
                            </h2>
                        </div>

                        <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                            <i data-lucide="user-round" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">អត្តលេខ</p>
                            <p class="font-semibold text-gray-800">
                                {{ $employee->id_code ?? 'មិនមាន' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">គោត្តនាមនិងនាម</p>
                            <p class="font-semibold text-gray-800">
                                {{ $employee->name_kh ?? ($employee->name ?? 'មិនមាន') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">មុខតំណែង</p>
                            <p class="font-semibold text-gray-800">
                                {{ $employee->position ?? 'មិនមាន' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 mb-1">វគ្គវាយតម្លៃ</p>
                            <p class="font-semibold text-gray-800">
                                {{ $evaluationPeriod->name_kh }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Evaluator count --}}
            <div class="flex items-center justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        អ្នកវាយតម្លៃ
                    </h2>
                    <p class="mt-1 text-sm text-gray-500">
                        បង្ហាញតែការវាយតម្លៃឥរិយាបថដែលបានដាក់ស្នើរួច
                    </p>
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-purple-50 text-purple-700 text-sm font-semibold">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    {{ $evaluations->count() }} នាក់
                </span>
            </div>

            @if ($evaluations->isEmpty())
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-6 py-12 text-center">
                    <div class="mx-auto w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 mb-4">
                        <i data-lucide="clipboard-x" class="w-7 h-7"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800">
                        មិនមានការវាយតម្លៃឥរិយាបថ
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        មិនទាន់មានអ្នកវាយតម្លៃណាបានដាក់ស្នើការវាយតម្លៃសម្រាប់មន្ត្រីនេះទេ។
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
                    @foreach ($evaluations as $evaluation)
                        @php
                            $evaluator = $evaluation->evaluator;
                            $behavior = $evaluation->behavior;
                            $total = (float) ($behavior?->total_score ?? 0);
                        @endphp

                        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                            {{-- Evaluator card header --}}
                            <div class="px-6 py-4 border-b border-gray-100">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                            <i data-lucide="user-check" class="w-5 h-5"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-xs text-gray-500 mb-0.5">
                                                អ្នកវាយតម្លៃ
                                            </p>
                                            <h3 class="font-bold text-gray-800 truncate">
                                                {{ $evaluator?->name_kh ?? ($evaluator?->name ?? 'មិនមានឈ្មោះ') }}
                                            </h3>
                                            @if ($evaluator?->position)
                                                <p class="mt-0.5 text-xs text-gray-500 truncate">
                                                    {{ $evaluator->position }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Score summary --}}
                            <div class="p-6">
                                <div class="rounded-2xl border border-purple-100 bg-purple-50 px-5 py-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
                                        <div>
                                            <p class="mt-1 text-sm text-gray-500">
                                                ពិន្ទុសរុបដែលអ្នកវាយតម្លៃបានផ្តល់
                                            </p>
                                        </div>

                                        <div class="flex items-baseline gap-1">
                                            <span
                                                class="behavior-card-total text-sm font-extrabold text-purple-700"
                                                data-evaluation-total="{{ $evaluation->evaluation_id }}"
                                            >
                                                {{ rtrim(rtrim(number_format($total, 2), '0'), '.') }}
                                            </span>
                                            <span class="text-sm font-semibold text-purple-500">/20</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="text-xs text-gray-500">
                                        <span class="font-medium text-gray-600">កាលបរិច្ឆេទបានវាយតម្លៃ៖</span>
                                        {{ $evaluation->submitted_at?->format('d/m/Y H:i') ?? 'មិនមាន' }}
                                    </div>

                                    {{-- Step 3 will connect this button to the edit modal. --}}
                                    <button
                                        type="button"
                                        class="behavior-edit-btn inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition"
                                        data-evaluation-id="{{ $evaluation->evaluation_id }}"
                                        data-update-url="{{ route('report.behavior.update', [
                                            'evaluationPeriod' => $evaluationPeriod->evaluation_period_id,
                                            'user' => $employee->user_id,
                                            'evaluation' => $evaluation->evaluation_id,
                                        ]) }}"
                                        data-evaluator-name="{{ $evaluator?->name_kh ?? ($evaluator?->name ?? 'មិនមានឈ្មោះ') }}"
                                        data-discipline="{{ (int) ($behavior?->discipline ?? 0) }}"
                                        data-responsibility="{{ (int) ($behavior?->responsibility ?? 0) }}"
                                        data-professional-ethics="{{ (int) ($behavior?->professional_ethics ?? 0) }}"
                                        data-work-performance="{{ (int) ($behavior?->work_performance ?? 0) }}"
                                        data-self-development="{{ (int) ($behavior?->self_development ?? 0) }}"
                                        data-initiative-creativity="{{ (int) ($behavior?->initiative_creativity ?? 0) }}"
                                        data-teamwork="{{ (int) ($behavior?->teamwork ?? 0) }}"
                                        data-interpersonal-skill="{{ (int) ($behavior?->interpersonal_skill ?? 0) }}"
                                        data-work-under-pressure="{{ (int) ($behavior?->work_under_pressure ?? 0) }}"
                                        data-leadership="{{ (int) ($behavior?->leadership ?? 0) }}"
                                    >
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                        កែប្រែ
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

    <div data-behavior-review-url="{{ route('report.behavior-review', ['evaluationPeriod' => $evaluationPeriod->evaluation_period_id, 'user' => $employee->user_id]) }}" class="hidden"></div>

    {{-- Success modal --}}
    <div
        id="behaviorEditSuccessModal"
        class="fixed inset-0 z-[130] hidden"
        aria-hidden="true"
    >
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div
                class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden"
                role="dialog"
                aria-modal="true"
                aria-labelledby="behaviorEditSuccessTitle"
            >
                <div class="px-6 py-7 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-600">
                        <i data-lucide="circle-check" class="w-9 h-9"></i>
                    </div>

                    <h2 id="behaviorEditSuccessTitle" class="mt-4 text-xl font-bold text-gray-800">
                        បានរក្សាទុកដោយជោគជ័យ
                    </h2>

                    <p id="behaviorEditSuccessMessage" class="mt-2 text-sm leading-6 text-gray-500">
                        ពិន្ទុត្រូវបានកែប្រែដោយជោគជ័យ។
                    </p>

                    {{-- <div class="mt-6 rounded-xl bg-green-50 px-4 py-3">
                        <p class="text-sm text-green-700">
                            កំពុងត្រឡប់ទៅទំព័រពិនិត្យលទ្ធផល...
                        </p>
                    </div> --}}

                    <button
                        type="button"
                        id="behaviorEditSuccessContinue"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-purple-600 px-5 py-3 text-sm font-semibold text-white hover:bg-purple-700 transition"
                    >
                        ត្រឡប់ទៅពិនិត្យលទ្ធផល
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         Step 3: Edit Behavior Modal
         UI follows the same 3 sections used in the Behavior Evaluation Create page.
         ================================================================ --}}
    <div
        id="behaviorEditModal"
        class="fixed inset-0 z-[100] hidden"
        aria-hidden="true"
    >
        <div
            id="behaviorEditBackdrop"
            class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"
        ></div>

        <div class="relative flex min-h-full items-center justify-center p-4 sm:p-6">
            <div
                id="behaviorEditDialog"
                role="dialog"
                aria-modal="true"
                aria-labelledby="behaviorEditTitle"
                class="relative flex w-full max-w-4xl max-h-[94vh] flex-col overflow-hidden rounded-2xl bg-white shadow-2xl"
            >
                {{-- Modal header --}}
                <div class="shrink-0 flex items-start justify-between gap-4 border-b border-gray-200 px-6 py-5 bg-gray-50">
                    <div class="min-w-0">
                        <p class="text-xs font-medium text-blue-600">
                            កែប្រែការវាយតម្លៃ
                        </p>
                        <h2 id="behaviorEditTitle" class="mt-1 text-xl font-bold text-gray-800">
                            ការវាយតម្លៃឥរិយាបថ
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            អ្នកវាយតម្លៃ៖
                            <span id="behaviorEditEvaluator" class="font-semibold text-gray-700">—</span>
                        </p>
                    </div>

                    <button
                        type="button"
                        id="behaviorEditClose"
                        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition"
                        aria-label="បិទ"
                    >
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form
                    id="behaviorEditForm"
                    method="POST"
                    class="min-h-0 flex flex-1 flex-col"
                >
                    @csrf
                    @method('PATCH')

                    {{-- Scrollable form content --}}
                    <div class="min-h-0 flex-1 overflow-y-auto px-6 py-6">

                        {{-- Live total --}}
                        <div class="mb-6 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-semibold text-blue-600">
                                        ពិន្ទុសរុប
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">
                                        សរុបអតិបរមា 20 ពិន្ទុ
                                    </p>
                                </div>

                                <div class="flex items-baseline gap-1 shrink-0">
                                    <span
                                        id="behaviorEditTotal"
                                        class="text-sm font-extrabold text-blue-500"
                                    >0</span>
                                    <span class="text-sm font-semibold text-blue-500">/20</span>
                                </div>
                            </div>
                        </div>

                        {{-- =====================================================
                             Section 1 — 6 points
                             Same structure/content as create.blade.php
                             ====================================================== --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden mb-6">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    ១. ឥរិយាបថ និងវិន័យ
                                </h3>

                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium">
                                    6 ពិន្ទុ
                                </span>
                            </div>

                            <div>
                                @php
                                    $sectionOne = [
                                        'discipline' => 'គោរពវិន័យការងារ ម៉ោងពេលធ្វើការ និងបទបញ្ជាផ្ទៃក្នុងរបស់អង្គភាព',
                                        'responsibility' => 'ស្មារតីទទួលខុសត្រូវ',
                                        'professional_ethics' => 'ការគោរពឋានានុក្រមការងារ និងគោរពការសម្ងាត់វិជ្ជាជីវៈ និងកាតព្វកិច្ចលក្ខណការណ៍',
                                    ];
                                @endphp

                                @foreach ($sectionOne as $key => $label)
                                    <div class="px-6 py-5 border-b border-gray-100 last:border-b-0">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700 leading-7">
                                                    {{ $loop->iteration }}. {{ $label }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-6 shrink-0">
                                                @for ($score = 0; $score <= 2; $score++)
                                                    <label class="flex items-center gap-2 cursor-pointer text-gray-700">
                                                        <input
                                                            type="radio"
                                                            name="{{ $key }}"
                                                            value="{{ $score }}"
                                                            class="behavior-score-input w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500"
                                                            data-score-field="{{ $key }}"
                                                        >
                                                        <span class="text-sm">{{ $score }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- =====================================================
                             Section 2 — 6 points
                             ====================================================== --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden mb-6">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    ២. សមត្ថភាពវិជ្ជាជីវៈ
                                </h3>

                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium">
                                    6 ពិន្ទុ
                                </span>
                            </div>

                            <div>
                                @php
                                    $sectionTwo = [
                                        'work_performance' => 'សមត្ថភាពបំពេញការងារ',
                                        'self_development' => 'ឆន្ទៈក្នុងការអភិវឌ្ឍសមត្ថភាព ចំណេះដឹង និងជំនាញ',
                                        'initiative_creativity' => 'មានគំនិតផ្តួចផ្តើម និងច្នៃប្រឌិត',
                                    ];
                                @endphp

                                @foreach ($sectionTwo as $key => $label)
                                    <div class="px-6 py-5 border-b border-gray-100 last:border-b-0">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700 leading-7">
                                                    {{ $loop->iteration }}. {{ $label }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-6 shrink-0">
                                                @for ($score = 0; $score <= 2; $score++)
                                                    <label class="flex items-center gap-2 cursor-pointer text-gray-700">
                                                        <input
                                                            type="radio"
                                                            name="{{ $key }}"
                                                            value="{{ $score }}"
                                                            class="behavior-score-input w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500"
                                                            data-score-field="{{ $key }}"
                                                        >
                                                        <span class="text-sm">{{ $score }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- =====================================================
                             Section 3 — 8 points
                             ====================================================== --}}
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    ៣. ភាពជាអ្នកដឹកនាំ
                                </h3>

                                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-medium">
                                    8 ពិន្ទុ
                                </span>
                            </div>

                            <div>
                                @php
                                    $sectionThree = [
                                        'teamwork' => 'សហការជាមួយមន្ត្រីរាជការដទៃដើម្បីសម្រេចលទ្ធផលរួម / ស្មារតីជាក្រុម',
                                        'interpersonal_skill' => 'ទំនាក់ទំនងអន្តរបុគ្គល',
                                        'work_under_pressure' => 'សមត្ថភាពអនុវត្តការងារក្រោមសម្ពាធ',
                                        'leadership' => 'សមត្ថភាពភាពជាអ្នកដឹកនាំ',
                                    ];
                                @endphp

                                @foreach ($sectionThree as $key => $label)
                                    <div class="px-6 py-5 border-b border-gray-100 last:border-b-0">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-700 leading-7">
                                                    {{ $loop->iteration }}. {{ $label }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-6 shrink-0">
                                                @for ($score = 0; $score <= 2; $score++)
                                                    <label class="flex items-center gap-2 cursor-pointer text-gray-700">
                                                        <input
                                                            type="radio"
                                                            name="{{ $key }}"
                                                            value="{{ $score }}"
                                                            class="behavior-score-input w-4 h-4 text-purple-600 border-gray-300 focus:ring-purple-500"
                                                            data-score-field="{{ $key }}"
                                                        >
                                                        <span class="text-sm">{{ $score }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Modal footer --}}
                    <div class="shrink-0 flex flex-col-reverse gap-2 border-t border-gray-200 bg-white px-6 py-5 sm:flex-row sm:justify-end">
                        <button
                            type="button"
                            id="behaviorEditCancel"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
                        >
                            បោះបង់
                        </button>

                        <button
                            type="submit"
                            id="behaviorEditSubmit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-purple-700 transition disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <i data-lucide="save" class="w-4 h-4"></i>
                            រក្សាទុកការកែប្រែ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    @vite('resources/js/evaluations/behavior/review.js')
@endsection
