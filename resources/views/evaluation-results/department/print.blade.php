@php
    use App\Helpers\KhmerHelper;
@endphp
<!DOCTYPE html>
<html lang="km">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        របាយការណ៍វាយតម្លៃ
    </title>
    @vite(['resources/css/app.css'])
    @vite(['resources/css/print.css'])

</head>


<body>
    {{-- ==========================================
         Print Button
    ========================================== --}}
    <div class="print-actions">
        <button type="button" class="print-button" onclick="window.print()">
            🖨 បោះពុម្ព
        </button>
    </div>


    <div class="print-container">


        {{-- ==========================================
             Official Header
        ========================================== --}}

        <div class="official-header">


            {{-- Left --}}

            <div class="header-left">

                {{-- logo --}}

                <img src="{{ asset('images/logo.png') }}" class="ministry-logo" alt="Logo">

                <div class="ministry-name">
                    ក្រសួងការងារនិងបណ្តុះបណ្តាលវិជ្ជាជីវៈ
                </div>

            </div>


            {{-- Right --}}

            <div class="header-right">

                <div>
                    ព្រះរាជាណាចក្រកម្ពុជា
                </div>

                <div>
                    ជាតិ សាសនា ព្រះមហាក្សត្រ
                </div>

                <img src="{{ asset('images/taktaing.png') }}" class="header-right-divider" alt="Taktaing">

            </div>


            {{-- Center title --}}

            <div class="report-title">

                របាយការណ៍វាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី

                <br>

                {{ $result->evaluationPeriodUser?->user?->department?->name_kh ?? '' }}

                ប្រចាំខែ
                {{ KhmerHelper::month($evaluationPeriod->month) }}
                ឆ្នាំ
                {{ KhmerHelper::number($evaluationPeriod->year) }}

            </div>

        </div>


        {{-- ==========================================
             Evaluation Table
        ========================================== --}}
        <table class="report-table">

            <thead>

                <tr>

                    <th class="col-no">
                        ល.រ
                    </th>

                    <th class="col-name">
                        គោត្តនាម និងនាម
                    </th>

                    <th class="col-position">
                        តួនាទី
                    </th>

                    <th class="col-performance">
                        សមិទ្ធកម្មការងារ
                    </th>

                    <th class="col-attendance">
                        វត្តមាន
                    </th>

                    <th class="col-behavior">
                        អាកប្បកិរិយា
                    </th>

                    <th class="col-total">
                        ពិន្ទុវាយតម្លៃ
                    </th>

                    <th class="col-remarks">
                        មូលវិចារណ៍
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr>

                    <td>
                        ១
                    </td>

                    <td class="text-left">
                        {{ $result->evaluationPeriodUser?->user?->name_kh ?? 'មិនមាន' }}
                    </td>

                    <td>
                        មន្ត្រី
                    </td>

                    <td>
                        {{ number_format($result->work_performance_score ?? 0, 2) }}
                    </td>

                    <td>
                        {{ number_format($result->attendance_score ?? 0, 2) }}
                    </td>

                    <td>
                        {{ number_format($result->behavior_score ?? 0, 2) }}
                    </td>

                    <td class="total-score">
                        {{ number_format($result->total_score ?? 0, 2) }}/100
                    </td>

                    <td class="text-left">
                        {{ $result->remarks ?? 'មិនមាន' }}
                    </td>
                </tr>
            </tbody>

        </table>


        {{-- ==========================================
             Date / Signature
        ========================================== --}}

        <div class="bottom-section">

            <div class="date-signature">
                <div>
                    {{ KhmerHelper::lunarDate(now()) }}
                </div>

                <div>
                    ត្រូវនឹង{{ KhmerHelper::fullDate(now()) }}
                </div>
                <div class="evaluator">
                    ប្រធាននាយកដ្ឋាន
                </div>

                <div class="signature-space"></div>

                
                <div class="evaluator-name">
                    {{ $departmentAdmin->name_kh }}
                </div>

            </div>

        </div>

    </div>


    <script>
        window.addEventListener("load", () => {
            window.print();
        });
    </script>

</body>

</html>
