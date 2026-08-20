@php

    $total = $evaluation->total_score;

    if ($total >= 90) {

        $rating = 'ល្អណាស់';

        $description =
            'មន្ត្រីរាជការបានបំពេញការងារបានយ៉ាងមានប្រសិទ្ធភាព មានការទទួលខុសត្រូវខ្ពស់ និងសម្រេចគោលដៅការងារបានយ៉ាងល្អប្រសើរ។';

        $bg = 'from-blue-600 to-indigo-700';
        $badge = 'bg-green-100 text-green-700';
        $border = 'border-green-200 bg-green-50';

    } elseif ($total >= 80) {

        $rating = 'ល្អ';

        $description =
            'មន្ត្រីរាជការបានបំពេញការងារបានល្អ និងសម្រេចគោលដៅការងារភាគច្រើន។';

        $bg = 'from-green-600 to-emerald-700';
        $badge = 'bg-green-100 text-green-700';
        $border = 'border-green-200 bg-green-50';

    } elseif ($total >= 70) {

        $rating = 'ល្អបង្គួរ';

        $description =
            'មន្ត្រីរាជការបានបំពេញការងារបានត្រឹមត្រូវ ប៉ុន្តែនៅមានចំណុចដែលត្រូវកែលម្អ។';

        $bg = 'from-yellow-500 to-orange-500';
        $badge = 'bg-yellow-100 text-yellow-700';
        $border = 'border-yellow-200 bg-yellow-50';

    } elseif ($total >= 60) {

        $rating = 'មធ្យម';

        $description =
            'មន្ត្រីរាជការគួរបង្កើនប្រសិទ្ធភាព និងការទទួលខុសត្រូវក្នុងការងារ។';

        $bg = 'from-orange-500 to-red-500';
        $badge = 'bg-orange-100 text-orange-700';
        $border = 'border-orange-200 bg-orange-50';

    } else {

        $rating = 'ត្រូវកែលម្អ';

        $description =
            'មន្ត្រីរាជការត្រូវកែលម្អការបំពេញការងារ និងអនុវត្តផែនការអភិវឌ្ឍន៍បន្ថែម។';

        $bg = 'from-red-600 to-red-700';
        $badge = 'bg-red-100 text-red-700';
        $border = 'border-red-200 bg-red-50';

    }

@endphp

<div class="mt-6 rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">

    {{-- Header --}}
    <div class="border-b px-6 py-4">

        <h3 class="text-lg font-semibold text-gray-800">
            🏆 លទ្ធផលសរុប
        </h3>

        <p class="mt-1 text-sm text-gray-500">
            លទ្ធផលពិន្ទុការវាយតម្លៃសរុប
        </p>

    </div>

    {{-- Overall Score --}}
    <div class="bg-gradient-to-r {{ $bg }} px-8 py-10 text-center text-white">

        <p class="text-lg opacity-90">
            ពិន្ទុសរុប
        </p>

        <h1 class="mt-3 text-7xl font-bold">

            {{ number_format($total, 2) }}

        </h1>

        <p class="text-2xl opacity-80">
            / 100
        </p>

        <span class="mt-6 inline-block rounded-full bg-white/20 px-6 py-2 text-xl font-semibold">

            {{ $rating }}

        </span>

    </div>

    {{-- Breakdown --}}
    {{-- <div class="divide-y">

        <div class="flex items-center justify-between px-6 py-4">

            <span>
                សមិទ្ធផលការងារ
            </span>

            <span class="font-semibold">

                {{ number_format($evaluation->work_performance_score,2) }}
                / 60

            </span>

        </div>

        <div class="flex items-center justify-between px-6 py-4">

            <span>
                វត្តមានការងារ
            </span>

            <span class="font-semibold">

                {{ number_format($evaluation->attendance_score,2) }}
                / 20

            </span>

        </div>

        <div class="flex items-center justify-between px-6 py-4">

            <span>
                ឥរិយាបថ និងសមត្ថភាព
            </span>

            <span class="font-semibold">

                {{ number_format($evaluation->behavior_score,2) }}
                / 20

            </span>

        </div>

        <div class="flex items-center justify-between bg-gray-50 px-6 py-4">

            <span class="font-semibold text-gray-800">

                ពិន្ទុសរុប

            </span>

            <span class="text-xl font-bold text-blue-600">

                {{ number_format($total,2) }}
                / 100

            </span>

        </div>

    </div> --}}

    {{-- Rating --}}
    <div class="border-t {{ $border }} p-6">

        <span class="inline-flex rounded-full px-4 py-2 text-sm font-semibold {{ $badge }}">

            {{ $rating }}

        </span>

        <p class="mt-4 leading-8 text-gray-700">

            {{ $description }}

        </p>

    </div>

</div>