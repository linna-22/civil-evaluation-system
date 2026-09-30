@extends('layouts.app')

@section('title', 'វត្តមាន')

@section('content')
<div class="min-h-screen bg-[#f5f8ff]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">វត្តមាន</h1>
                <p class="mt-1 text-sm text-gray-500">ពិនិត្យលទ្ធផលវត្តមានរបស់មន្ត្រីក្នុងនាយកដ្ឋាន</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{ route('evaluation-results.work-performance.index', ['evaluationPeriod' => $evaluationPeriod->evaluation_period_id]) }"
                   class="px-3 py-2 rounded-lg text-sm { $module === 'work-performance' ? 'bg-blue-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }">សមិទ្ធកម្មការងារ</a>
                <a href="{ route('evaluation-results.attendance.index', ['evaluationPeriod' => $evaluationPeriod->evaluation_period_id]) }"
                   class="px-3 py-2 rounded-lg text-sm { $module === 'attendance' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }">វត្តមាន</a>
                <a href="{ route('evaluation-results.behavior.index', ['evaluationPeriod' => $evaluationPeriod->evaluation_period_id]) }"
                   class="px-3 py-2 rounded-lg text-sm { $module === 'behavior' ? 'bg-purple-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }">ឥរិយាបថ</a>
                <a href="{ route('evaluation-results.overall.index', ['evaluationPeriod' => $evaluationPeriod->evaluation_period_id]) }"
                   class="px-3 py-2 rounded-lg text-sm { $module === 'overall' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-600 border border-gray-200' }">លទ្ធផលរួម</a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs text-gray-500">វគ្គវាយតម្លៃ</p>
                    <p class="mt-1 font-bold text-gray-800">{ $evaluationPeriod->name_kh }</p>
                </div>

                <form method="GET" class="flex flex-col sm:flex-row gap-2">
                    <input type="text" name="search" value="{ request('search') }"
                           placeholder="ស្វែងរកមន្ត្រី..."
                           class="w-full sm:w-64 rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <select name="per_page" class="rounded-lg border-gray-200 text-sm">
                        @foreach([10, 20, 50] as $size)
                            <option value="{ $size }" @selected((int)request('per_page', 10) === $size)>{ $size } ជួរ</option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm font-semibold">ស្វែងរក</button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold text-gray-600">ល.រ</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-600">អត្តលេខ</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-600">គោត្តនាម និងនាម</th>
                            <th class="px-5 py-3 text-left font-semibold text-gray-600">មុខតំណែង</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600">ពិន្ទុ</th>
                            <th class="px-5 py-3 text-center font-semibold text-gray-600">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($results as $result)
                            @php $employee = $result->evaluationPeriodUser?->user; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-gray-500">{ $results->firstItem() + $loop->index }</td>
                                <td class="px-5 py-4 font-medium text-gray-700">{ $employee?->id_code ?? '—' }</td>
                                <td class="px-5 py-4 font-semibold text-gray-800">{ $employee?->name_kh ?? 'មិនមាន' }</td>
                                <td class="px-5 py-4 text-gray-600">
                                    { config('positions.options.' . ($employee?->position ?? ''), $employee?->position ?? 'មិនមាន') }
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 font-bold">
                                        { number_format($score_expr, 2) }/{ $max_score }
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{ route('evaluation-results.attendance.view', ['evaluationPeriod' => $evaluationPeriod->evaluation_period_id, 'user' => $employee->user_id]) }"
                                       class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white border border-emerald-200 text-emerald-600 text-xs font-semibold hover:bg-emerald-50 transition">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        មើល
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500">មិនមានទិន្នន័យសម្រាប់វគ្គនេះទេ។</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($results->hasPages())
            <div class="mt-5">{ $results->withQueryString()->links() }</div>
        @endif
    </div>
</div>
@endsection
