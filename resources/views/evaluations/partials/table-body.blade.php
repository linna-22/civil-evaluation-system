@php
    $role = auth()->user()->role;

    $showOrganization = $role === 'super_admin';

    $showDepartment = in_array($role, [
        'super_admin',
        'organization_admin',
    ]);
@endphp
<tbody class="divide-y divide-gray-100 bg-white text-xs font-body" id="tableBody">

    @forelse($evaluations as $evaluation)

        <tr class="hover:bg-gray-50 transition">

            {{-- No --}}
            <td class="px-6 py-4 text-center text-xs text-gray-700">
                {{ $loop->iteration + ($evaluations->firstItem() - 1) }}
            </td>

            {{-- Name --}}
            <td class="px-6 py-4">
                <div>
                    <p class="text-sm text-gray-800">
                        {{ $evaluation->user->name_kh }}
                    </p>
                </div>
            </td>

            {{-- Organization --}}
            @if($showOrganization)
                <td class="px-6 py-4 text-sm text-gray-700">
                    {{ $evaluation->user->organization->org_name_kh ?? '-' }}
                </td>
            @endif
            {{-- Department --}}
            @if($showDepartment)
                <td class="px-6 py-4 text-sm text-gray-700">
                    {{ $evaluation->user->department->department_name_kh ?? '-' }}
                </td>
            @endif
            {{-- Month --}}
            <td class="px-6 py-4 text-center text-sm">
                {{ \Carbon\Carbon::create()->month($evaluation->evaluation_month)->translatedFormat('F') }}
            </td>
            {{-- Year --}}
            <td class="px-6 py-4 text-center text-sm">
                {{ $evaluation->evaluation_year }}
            </td>
            {{-- Total Score --}}
            <td class="px-6 py-4 text-center">
                @php
                    $score = $evaluation->total_score;
                    if ($score >= 90) {
                        $class = 'bg-green-100 text-green-700';
                    } elseif ($score >= 80) {
                        $class = 'bg-blue-100 text-blue-700';
                    } elseif ($score >= 70) {
                        $class = 'bg-yellow-100 text-yellow-700';
                    } else {
                        $class = 'bg-red-100 text-red-700';
                    }
                @endphp
                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $class }}">
                    {{ number_format($score, 2) }}
                </span>
            </td>
            {{-- Submitted At --}}
            <td class="px-6 py-4 text-center text-sm text-gray-600">
                {{ \App\Helpers\DateHelper::khmerDateTime($evaluation->submitted_at) }}
            </td>
            {{-- Action --}}
            <td class="px-6 py-4 text-center">
                <a
                    href="{{ route('evaluations.show', $evaluation) }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600 transition hover:bg-blue-100">
                    <i data-lucide="eye" class="h-4 w-4"></i>
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td
                colspan="{{ auth()->user()->role === 'super_admin' ? 10 : (auth()->user()->role === 'organization_admin' ? 9 : 8) }}"
                class="px-6 py-20 text-center">
                <div class="flex flex-col items-center">
                    <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-200">
                        <i data-lucide="clipboard-list" class="h-8 w-8 text-red-400"></i>
                    </div>
                    <h4 class="font-semibold text-red-400">
                        មិនមានទិន្នន័យ
                    </h4>
                    {{-- <p class="mt-2 text-sm text-gray-500">
                        មិនទាន់មានការវាយតម្លៃ
                    </p> --}}
                </div>
            </td>
        </tr>
    @endforelse
</tbody>