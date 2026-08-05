@if($evaluations->hasPages())

    <div class="border-t border-gray-100 bg-white px-6 py-4">

        <div class="flex flex-col items-center justify-between gap-4 md:flex-row">

            <div class="text-sm text-gray-600">
                បង្ហាញ
                <span class="font-semibold">
                    {{ $evaluations->firstItem() }}
                </span>
                ដល់
                <span class="font-semibold">
                    {{ $evaluations->lastItem() }}
                </span>
                នៃ
                <span class="font-semibold">
                    {{ $evaluations->total() }}
                </span>
                ទិន្នន័យ
            </div>
            {{ $evaluations->links() }}
        </div>

    </div>

@endif