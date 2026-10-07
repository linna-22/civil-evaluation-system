@extends('layouts.app')
@section('title','មើលឥរិយាបថ')
@section('content')
<div class="max-w-6xl mx-auto py-6">
    <div class="flex items-center justify-between mb-5">
        <div><h1 class="text-2xl font-bold text-gray-800">ឥរិយាបថ</h1><p class="text-sm text-gray-500">{{ $evaluationPeriod->name_kh }}</p></div>
        <a href="{{ route('evaluation-results.behavior.index',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id]) }}" class="px-4 py-2 rounded-lg bg-white border text-gray-700">ត្រឡប់</a>
    </div>
    @php $employee=$user; @endphp
    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div><p class="text-xs text-gray-500">អត្តលេខ</p><p class="font-semibold">{{ $employee?->id_code ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">គោត្តនាម និងនាម</p><p class="font-semibold">{{ $employee?->name_kh ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">ចំនួនអ្នកវាយតម្លៃ</p><p class="font-semibold">{{ $evaluations->count() }}</p></div>
        </div>
    </div>
    <div class="space-y-4">
        @forelse($evaluations as $evaluation)
            <div class="bg-white rounded-2xl border shadow-sm p-5 flex items-center justify-between gap-4">
                <div>
                    <p class="font-bold text-gray-800">{{ $evaluation->evaluator?->name_kh ?? 'មិនមាន' }}</p>
                    <p class="text-sm text-gray-500">ពិន្ទុសរុប: {{ number_format($evaluation->behavior?->total_score ?? 0,0) }}/20</p>
                </div>
                <a href="{{ route('evaluation-results.behavior.review',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id,'user'=>$employee->user_id]) }}" class="px-4 py-2 rounded-lg bg-purple-600 text-white">កែប្រែ</a>
            </div>
        @empty
            <div class="bg-white rounded-2xl border p-10 text-center text-gray-500">មិនមានលទ្ធផលឥរិយាបថ</div>
        @endforelse
    </div>
</div>
@endsection
