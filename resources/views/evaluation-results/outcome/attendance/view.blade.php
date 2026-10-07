@extends('layouts.app')
@section('title','មើលវត្តមាន')
@section('content')
<div class="max-w-5xl mx-auto py-6">
    <div class="flex items-center justify-between mb-5">
        <div><h1 class="text-2xl font-bold text-gray-800">វត្តមាន</h1><p class="text-sm text-gray-500">{{ $evaluationPeriod->name_kh }}</p></div>
        <a href="{{ route('evaluation-results.attendance.index',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id]) }}" class="px-4 py-2 rounded-lg bg-white border text-gray-700">ត្រឡប់</a>
    </div>
    @php $employee=$evaluation->evaluatee; $attendance=$evaluation->attendance; @endphp
    <div class="bg-white rounded-2xl border shadow-sm p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div><p class="text-xs text-gray-500">អត្តលេខ</p><p class="font-semibold">{{ $employee?->id_code ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">គោត្តនាម និងនាម</p><p class="font-semibold">{{ $employee?->name_kh ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">ពិន្ទុវត្តមាន</p><p class="text-2xl font-bold text-emerald-600">{{ number_format($evaluation->attendance_score ?? 0,2) }}/20</p></div>
            <div><p class="text-xs text-gray-500">ច្បាប់មានការអនុញ្ញាត</p><p class="font-semibold">{{ $attendance?->approved_leave_days ?? 0 }} ថ្ងៃ</p></div>
            <div><p class="text-xs text-gray-500">អវត្តមានគ្មានការអនុញ្ញាត</p><p class="font-semibold">{{ $attendance?->unapproved_leave_days ?? 0 }} ថ្ងៃ</p></div>
            <div><p class="text-xs text-gray-500">ម៉ោងយឺត</p><p class="font-semibold">{{ $attendance?->late_hours ?? 0 }}</p></div>
            <div><p class="text-xs text-gray-500">ម៉ោងចេញមុន</p><p class="font-semibold">{{ $attendance?->leave_early_hours ?? 0 }}</p></div>
            <div><p class="text-xs text-gray-500">ម៉ោងបន្ថែម</p><p class="font-semibold">{{ $attendance?->overtime_hours ?? 0 }}</p></div>
        </div>
    </div>
     <div class="mt-5 flex justify-end">
        <a href="{{ route('evaluation-results.attendance.edit',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id,'user'=>$employee->user_id]) }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white">កែប្រែ</a>
    </div>
</div>
@endsection
