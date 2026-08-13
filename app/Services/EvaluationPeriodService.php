<?php

namespace App\Services;

use App\Models\EvaluationPeriod;
use App\Models\EvaluationPeriodUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EvaluationPeriodService
{
    /**
     * Get evaluation periods for DataTable.
     */
    public function getData(Request $request)
    {
        $query = EvaluationPeriod::query();


        // ==========================================
        // Search
        // ==========================================

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_kh', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%");
            });
        }

        // ==========================================
        // Order
        // ==========================================

        return $query
            ->orderByDesc('year')
            ->orderByDesc('month')
            ->paginate(
                $request->get('per_page', 5)
            );
    }


    /**
     * Find an evaluation period.
     */
    public function find(EvaluationPeriod $evaluationPeriod): EvaluationPeriod
    {
        return $evaluationPeriod->load('periodUsers.user');
    }
    public function assignActiveUsers(
        EvaluationPeriod $evaluationPeriod
    ): void {

        $activeUserIds = User::query()
            ->where('status', 'active')
            ->pluck('user_id');

        if ($activeUserIds->isEmpty()) {
            return;
        }

        $now = now();

        $participants = $activeUserIds
            ->map(function ($userId) use ($evaluationPeriod, $now) {

                return [
                    'evaluation_period_id'
                    => $evaluationPeriod->evaluation_period_id,

                    'user_id'
                    => $userId,

                    'created_at' => $now,

                    'updated_at' => $now,
                ];

            })
            ->toArray();

        EvaluationPeriodUser::insertOrIgnore(
            $participants
        );
    }
    public function assignUserToOpenPeriods(
        User $user
    ): void {

        if ($user->status !== 'active') {
            return;
        }

        $openPeriods = EvaluationPeriod::query()
            ->where('status', 'open')
            ->get();

        if ($openPeriods->isEmpty()) {
            return;
        }

        $now = now();

        $participants = $openPeriods
            ->map(function ($period) use ($user, $now) {

                return [
                    'evaluation_period_id'
                    => $period->evaluation_period_id,

                    'user_id'
                    => $user->user_id,

                    'created_at' => $now,
                    'updated_at' => $now,
                ];

            })
            ->toArray();

        EvaluationPeriodUser::insertOrIgnore(
            $participants
        );
    }
    /**
     * Create an evaluation period
     * and automatically assign active users.
     */
    // public function store(array $data): EvaluationPeriod
    // {
    //     return DB::transaction(function () use ($data) {

    //         // ==========================================
    //         // Prevent duplicate month / year
    //         // ==========================================

    //         $exists = EvaluationPeriod::query()
    //             ->where('month', $data['month'])
    //             ->where('year', $data['year'])
    //             ->exists();

    //         if ($exists) {

    //             throw ValidationException::withMessages([
    //                 'month' =>
    //                     'វគ្គវាយតម្លៃសម្រាប់ខែ និងឆ្នាំនេះមានរួចហើយ។',
    //             ]);

    //         }


    //         // ==========================================
    //         // Create Evaluation Period
    //         // ==========================================

    //         $evaluationPeriod = EvaluationPeriod::create([

    //             'name_kh' => $data['name_kh'],
    //             'name_en' => $data['name_en'],
    //             'month' => $data['month'],
    //             'year' => $data['year'],
    //             'start_date' => $data['start_date'],
    //             'end_date' => $data['end_date'],
    //             'status' => 'open',
    //             'created_by' => auth()->id(),
    //             'open_at' => now(),
    //         ]);


    //         // ==========================================
    //         // Get Active Users
    //         // ==========================================

    //         $activeUsers = User::query()
    //             ->where('status', 'active')
    //             ->pluck('user_id');


    //         // ==========================================
    //         // Assign Active Users
    //         // ==========================================

    //         $participants = $activeUsers
    //             ->map(function ($userId) use ($evaluationPeriod) {

    //                 return [
    //                     'evaluation_period_id'
    //                     => $evaluationPeriod->evaluation_period_id,
    //                     'user_id'
    //                     => $userId,
    //                     'created_at'
    //                     => now(),
    //                     'updated_at'
    //                     => now(),
    //                 ];
    //             })
    //             ->toArray();

    //         if (!empty($participants)) {
    //             EvaluationPeriodUser::insert($participants);
    //         }

    //         return $evaluationPeriod->refresh();
    //     });
    // }
    public function store(array $data): EvaluationPeriod
    {
        return DB::transaction(function () use ($data) {

            // ==========================================
            // Prevent duplicate month / year
            // ==========================================

            $exists = EvaluationPeriod::query()
                ->where('month', $data['month'])
                ->where('year', $data['year'])
                ->exists();

            if ($exists) {

                throw ValidationException::withMessages([
                    'month' =>
                        'វគ្គវាយតម្លៃសម្រាប់ខែ និងឆ្នាំនេះមានរួចហើយ។',
                ]);

            }


            // ==========================================
            // Create Evaluation Period
            // ==========================================

            $evaluationPeriod = EvaluationPeriod::create([

                'name_kh' => $data['name_kh'],

                'name_en' => $data['name_en'],

                'month' => $data['month'],

                'year' => $data['year'],

                'start_date' => $data['start_date'],

                'end_date' => $data['end_date'],

                'status' => 'open',

                'created_by' => auth()->id(),

                'open_at' => now(),

            ]);


            // ==========================================
            // Assign Active Users
            // ==========================================

            $this->assignActiveUsers(
                $evaluationPeriod
            );


            return $evaluationPeriod->refresh();

        });
    }

    /**
     * Update an evaluation period.
     */
    public function update(EvaluationPeriod $evaluationPeriod, array $data): EvaluationPeriod
    {
        return DB::transaction(function () use ($evaluationPeriod, $data) {
            // ==========================================
            // Closed Period Protection
            // ==========================================

            if ($evaluationPeriod->status === 'closed') {

                throw ValidationException::withMessages([
                    'evaluation_period' =>
                        'វគ្គវាយតម្លៃដែលបានបិទ មិនអាចកែប្រែបានទេ។',
                ]);

            }


            // ==========================================
            // Check Duplicate Month / Year
            // ==========================================

            $exists = EvaluationPeriod::query()
                ->where('month', $data['month'])
                ->where('year', $data['year'])
                ->where(
                    'evaluation_period_id',
                    '!=',
                    $evaluationPeriod->evaluation_period_id
                )
                ->exists();

            if ($exists) {

                throw ValidationException::withMessages([
                    'month' =>
                        'វគ្គវាយតម្លៃសម្រាប់ខែ និងឆ្នាំនេះមានរួចហើយ។',
                ]);

            }


            // ==========================================
            // Update Evaluation Period
            // ==========================================

            $evaluationPeriod->update([
                'name_kh' => $data['name_kh'],
                'name_en' => $data['name_en'],
                'month' => $data['month'],
                'year' => $data['year'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ]);

            return $evaluationPeriod->refresh();

        });
    }


    /**
     * Close an evaluation period.
     */
    public function close(EvaluationPeriod $evaluationPeriod): EvaluationPeriod
    {
        return DB::transaction(function () use ($evaluationPeriod) {
            if ($evaluationPeriod->status === 'closed') {
                throw ValidationException::withMessages([
                    'evaluation_period' =>
                        'វគ្គវាយតម្លៃនេះបានបិទរួចហើយ។',
                ]);
            }

            $evaluationPeriod->update([
                'status' => 'closed',
                'closed_by' => auth()->id(),
                'close_at' => now(),
            ]);

            return $evaluationPeriod->refresh();

        });
    }

}