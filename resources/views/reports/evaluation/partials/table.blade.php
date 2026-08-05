<style>
    .report-table {

        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;

        font-family: "Battambang", sans-serif;
        font-size: 14px;

    }

    .report-table th {

        border: 1px solid #000;
        background: #F3F4F6;

        padding: 6px 8px;

        text-align: center;
        font-weight: bold;

        line-height: 1.2;

    }

    .report-table td {

        border: 1px solid #000;

        padding: 3px 8px;
        /* ↓ Smaller padding */

        line-height: 2;
        /* ↓ Smaller line height */

        vertical-align: middle;

    }
</style>
<div class="mt-8">

    <table class="report-table">

        <thead>

            <tr>

                <th style="width:10%">
                    ល.រ
                </th>

                <th style="width:20%">
                    គោត្តនាម និងនាម
                </th>

                <th style="width: 30%">
                    នាយកដ្ឋាន
                </th>

                <th style="width:20%">
                    មុខតំណែង
                </th>

                <th style="width:20%">
                    ពិន្ទុវាយតម្លៃសរុប
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($evaluations as $evaluation)
                <tr>

                    <td class="text-center">

                        {{ \App\Helpers\KhmerHelper::number($loop->iteration) }}

                    </td>

                    <td class="text-left">

                        {{ $evaluation->user->name_kh }}

                    </td>

                    <td class="text-left">

                        {{ $evaluation->user->department->department_name_kh }}

                    </td>

                    <td class="text-center">

                        {{ $evaluation->user->position }}

                    </td>

                    <td class="text-center">

                        {{ number_format($evaluation->total_score, 2) }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="empty">

                        មិនមានទិន្នន័យ

                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>

</div>
