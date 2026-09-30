@extends('layouts.app')
@section('title','មើលសមិទ្ធកម្មការងារ')
@section('content')
<div class="max-w-6xl mx-auto py-6">
    <div class="flex items-center justify-between mb-5">
        <div><h1 class="text-2xl font-bold text-gray-800">សមិទ្ធកម្មការងារ</h1><p class="text-sm text-gray-500">{{ $evaluationPeriod->name_kh }}</p></div>
        <a href="{{ route('evaluation-results.work-performance.index',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id]) }}" class="px-4 py-2 rounded-lg bg-white border text-gray-700">ត្រឡប់</a>
    </div>
    @php $employee=$evaluation->evaluatee; @endphp
    <div class="bg-white rounded-2xl border shadow-sm p-6 mb-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div><p class="text-xs text-gray-500">អត្តលេខ</p><p class="font-semibold">{{ $employee?->id_code ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">គោត្តនាម និងនាម</p><p class="font-semibold">{{ $employee?->name_kh ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500">ពិន្ទុសរុប</p><p class="font-bold text-blue-600">{{ number_format($evaluation->workPerformance->sum('achievement_percent'),2) }}</p></div>
        </div>
    </div>
    <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50"><tr><th class="px-5 py-3 text-left">សកម្មភាព</th><th class="px-5 py-3 text-left">សូចនាករ</th><th class="px-5 py-3 text-center">សមិទ្ធផល (%)</th></tr></thead>
            <tbody class="divide-y">
            @forelse($evaluation->workPerformance as $item)
                <tr><td class="px-5 py-3">{{ $item->activity }}</td><td class="px-5 py-3">{{ $item->indicator }}</td><td class="px-5 py-3 text-center">{{ number_format($item->achievement_percent,2) }}%</td></tr>
            @empty
                <tr><td colspan="3" class="px-5 py-10 text-center text-gray-500">មិនមានទិន្នន័យ</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-5 flex justify-end">
        <a href="{{ route('department-evaluation-results.work-performance.edit',['evaluationPeriod'=>$evaluationPeriod->evaluation_period_id,'user'=>$employee->user_id]) }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white">កែប្រែ</a>
    </div>
</div>
@endsection
