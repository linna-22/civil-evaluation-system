<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center">

        {{-- Step 1 --}}
        <div
            class="step-item flex flex-col items-center"
            data-progress="1">

            <div class="step-circle">
                1
            </div>

            <span class="mt-3 text-sm">
                សមិទ្ធកម្មការងារ
            </span>

        </div>

        <div class="flex-1 h-1 bg-gray-200 mx-3 progress-line"></div>

        {{-- Step 2 --}}
        <div
            class="step-item flex flex-col items-center"
            data-progress="2">

            <div class="step-circle">
                2
            </div>

            <span class="mt-3 text-sm">
                វត្តមាន
            </span>

        </div>

        <div class="flex-1 h-1 bg-gray-200 mx-3 progress-line"></div>

        {{-- Step 3 --}}
        <div
            class="step-item flex flex-col items-center"
            data-progress="3">

            <div class="step-circle">
                3
            </div>

            <span class="mt-3 text-sm">
                លក្ខណៈវិនិច្ឆ័យ
            </span>

        </div>

        <div class="flex-1 h-1 bg-gray-200 mx-3 progress-line"></div>

        {{-- Step 4 --}}
        <div
            class="step-item flex flex-col items-center"
            data-progress="4">

            <div class="step-circle">
                4
            </div>

            <span class="mt-3 text-sm">
                ពិនិត្យឡើងវិញ
            </span>

        </div>
        <div class="flex-1 h-1 bg-gray-200 mx-3 progress-line"></div>
        {{-- Step 5 --}}
        <div
            class="step-item flex flex-col items-center"
            data-progress="5">

            <div class="step-circle">
                5
            </div>

            <span class="mt-3 text-sm">
                លទ្ធផលវាយតម្លៃ
            </span>

        </div>

    </div>

</div>

<style>

.step-circle{
    width:48px;
    height:48px;
    border-radius:9999px;
    background:#E5E7EB;
    color:#374151;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:700;
    transition:all .3s ease;
}

.step-circle.active{
    background:#2563EB;
    color:#fff;
}

.step-circle.completed{
    background:#16A34A;
    color:#fff;
}

.progress-line{
    transition:all .3s ease;
}

.step-item span{
    transition:.3s;
}

</style>