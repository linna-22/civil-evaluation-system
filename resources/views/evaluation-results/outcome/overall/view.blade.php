@extends('layouts.app')
@section('title','លទ្ធផលវាយតម្លៃរួម')
@section('content')
<div class="max-w-6xl mx-auto py-6">
    <div class="flex items-center justify-between mb-5">
        <div><h1 class="text-2xl font-bold text-gray-800">លទ្ធផលវាយតម្លៃរួម</h1><p class="text-sm text-gray-500">{{ $evaluationPeriod->name_kh }}</p></div>
        <a href="{{ route('evaluation-results.overall.index',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id]) }}" class="px-4 py-2 rounded-lg bg-white border text-gray-700">ត្រឡប់</a>
    </div>
    @php $employee=$result->evaluationPeriodUser?->user; @endphp
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x">
            <div class="p-6"><p class="text-xs text-gray-500">មន្ត្រី</p><p class="font-bold">{{ $employee?->name_kh ?? '—' }}</p></div>
            <div class="p-6"><p class="text-xs text-gray-500">សមិទ្ធកម្មការងារ</p><p class="text-2xl font-bold text-blue-600">{{ number_format($result->work_performance_score ?? 0,2) }}/60</p></div>
            <div class="p-6"><p class="text-xs text-gray-500">វត្តមាន</p><p class="text-2xl font-bold text-emerald-600">{{ number_format($result->attendance_score ?? 0,2) }}/20</p></div>
            <div class="p-6"><p class="text-xs text-gray-500">ឥរិយាបថ</p><p class="text-2xl font-bold text-purple-600">{{ number_format($result->behavior_score ?? 0,2) }}/20</p></div>
        </div>
        <div class="p-6 border-t bg-indigo-50 text-center">
            <p class="text-sm text-gray-600">ពិន្ទុវាយតម្លៃសរុប</p>
            <p class="mt-1 text-4xl font-bold text-indigo-700">{{ number_format($result->total_score ?? 0,2) }}/100</p>
        </div>
    </div>
</div>
@endsection
