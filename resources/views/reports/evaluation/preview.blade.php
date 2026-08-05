<!DOCTYPE html>
<html lang="km">

<head>

    <meta charset="UTF-8">

    <title>របាយការណ៍វាយតម្លៃ</title>

    @vite(['resources/css/app.css'])
    <style>
        .signature-line {

            width: 220px;

            margin: auto;

            border-bottom: 1px solid black;

            margin-top: 80px;

        }
    </style>
</head>

<body class="bg-gray-200 py-10 font-body">

    <div class="mx-auto w-[210mm] min-h-[297mm] bg-white shadow-xl">

        {{-- Report Content --}}
        <div class="p-12">
            <div class="flex justify-between items-start">

                {{-- Left --}}

                <div class="text-center">

                    <img src="{{ asset('images/logo.png') }}" class="mx-auto h-24">

                    <p class="mt-3 font-moul leading-7 font-title" style="font-size: 12px">

                        ក្រសួងការងារនិងបណ្តុះបណ្តាលវិជ្ជាជីវៈ

                    </p>

                </div>
                {{-- Right --}}
                <div class="text-center leading-7">

                    <p class="font-moul font-title" style="font-size: 14px">

                        ព្រះរាជាណាចក្រកម្ពុជា

                    </p>

                    <p class="font-title" style="font-size: 14px">

                        ជាតិ សាសនា ព្រះមហាក្សត្រ

                    </p>

                    <div class="mt-2 flex justify-center" style="margin-left: 10px">

                        <img src="{{ asset('images/taktaing.png') }}" alt="Kingdom Divider"
                            class="h-5 object-contain">

                    </div>

                </div>

            </div>
        </div>
        <div class="mt-10 text-center">

            <h1 class="font-title" style="font-size: 16px">

                របាយការណ៍វាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី <br> របស់
                <span>{{ $organization?->org_name_kh ?? 'គ្រប់អង្គភាព' }}</span> ប្រចាំខែ
                <span>{{ \App\Helpers\KhmerHelper::month($filters['month']) }}</span> ឆ្នាំ
                <span>{{ \App\Helpers\KhmerHelper::number($filters['year']) }}</span>

            </h1>

        </div>
        <div class="px-8 mt-8">

            @include('reports.evaluation.partials.table')

        </div>
        <div class="mt-16">

            <div class="flex justify-end">

                <div class="w-80 text-center" style="margin-right: 30px;">

                    {{-- Khmer Lunar Date --}}
                    <p class="font-body" style="font-size: 12px">

                        {{ \App\Helpers\KhmerHelper::lunarDate($reportDate) }}

                    </p>

                    {{-- Current Date --}}
                    <p class="mt-1 font-body" style="font-size: 12px">

                        រាជធានីភ្នំពេញ {{ \App\Helpers\KhmerHelper::fullDate($reportDate) }}

                    </p>

                    {{-- Position --}}
                    <p class="mt-6 font-body" style="font-size: 14px">

                        អ្នកវាយតម្លៃ
                        
                    </p>

                    {{-- Signature Space --}}
                    <div class="mt-20"></div>

                    {{-- Leader Name --}}
                    <p class="mt-3 font-body font-semibold" style="font-size: 14px; margin-left: 20px;">

                        {{ $leader?->name_kh ?? '................................' }}

                    </p>

                </div>

            </div>

        </div>
    </div>

</body>

</html>
