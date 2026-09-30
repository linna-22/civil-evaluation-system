<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\EvaluationBehavior;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationPeriodUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BehaviorEvaluationService
{
    /**
     * Get the current open evaluation period.
     */
    public function getOpenEvaluationPeriod(): ?EvaluationPeriod
    {
        return EvaluationPeriod::query()
            ->where('status', 'open')
            ->first();
    }


    /**
     * Get eligible users for behavior evaluation.
     *
     * Behavior Evaluation Rule:
     *
     * - user can evaluate users in the same department
     * - office_admin can evaluate users in the same department
     * - department_admin cannot act as a peer evaluator
     * - evaluator cannot evaluate himself/herself
     * - department_admin cannot be an evaluation target
     * - users from another department cannot be evaluated
     * - office does NOT matter
     */
    public function getEligiblePeers()
    {
        $user = auth()->user();

        // =====================================================
        // Allowed Evaluator Roles
        // =====================================================

        if (
            !in_array($user->role, [
                'user',
                'office_admin',
            ], true)
        ) {
            abort(403);
        }


        // =====================================================
        // Get Open Evaluation Period
        // =====================================================

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return collect();
        }


        // =====================================================
        // Check Participant Snapshot
        // =====================================================

        $isParticipant = EvaluationPeriodUser::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'user_id',
                $user->user_id
            )
            ->exists();

        if (!$isParticipant) {
            return collect();
        }


        // =====================================================
        // Base Query
        // =====================================================

        $query = User::query()

            // -------------------------------------------------
            // Active users only
            // -------------------------------------------------
            ->where('status', 'active')

            // -------------------------------------------------
            // Department Admin cannot be evaluated
            // -------------------------------------------------
            ->where('role', '!=', 'department_admin')

            // -------------------------------------------------
            // Cannot evaluate yourself
            // -------------------------------------------------
            ->where(
                'user_id',
                '!=',
                $user->user_id
            )

            // -------------------------------------------------
            // Must exist in evaluation snapshot
            // -------------------------------------------------
            ->whereHas(
                'evaluationPeriodUsers',
                function ($query) use ($evaluationPeriod) {
                    $query->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    );
                }
            )

            // -------------------------------------------------
            // Same organization
            // -------------------------------------------------
            ->where(
                'organization_id',
                $user->organization_id
            )

            // -------------------------------------------------
            // Same department
            // -------------------------------------------------
            ->where(
                'department_id',
                $user->department_id
            );


        // =====================================================
        // IMPORTANT:
        //
        // Do NOT filter by office_id.
        //
        // Behavior Evaluation is department-level.
        // Different offices inside the same department
        // can evaluate each other.
        // =====================================================

        $peers = $query
            ->orderBy('name_kh')
            ->get();


        // =====================================================
        // Attach Evaluation Status
        // =====================================================

        foreach ($peers as $peer) {

            $evaluation = Evaluation::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'evaluator_id',
                    $user->user_id
                )
                ->where(
                    'evaluatee_id',
                    $peer->user_id
                )
                ->where(
                    'evaluation_type',
                    'behavior'
                )
                ->first();

            $peer->evaluation_status =
                $evaluation?->evaluation_status;
        }


        return $peers;
    }
    /**
     * Get paginated eligible users for the behavior evaluation index page.
     *
     * IMPORTANT:
     * This method is ONLY for displaying the employee table.
     * It does NOT replace getEligiblePeers(), because the create
     * page needs the complete list for the one-by-one evaluation flow.
     */
    public function getPaginatedEligiblePeers(int $perPage = 5)
    {
        $user = auth()->user();

        // =====================================================
        // Allowed Evaluator Roles
        // =====================================================

        if (
            !in_array($user->role, [
                'user',
                'office_admin',
            ], true)
        ) {
            abort(403);
        }

        // =====================================================
        // Get Open Evaluation Period
        // =====================================================

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return User::query()
                ->whereRaw('1 = 0')
                ->paginate($perPage);
        }

        // =====================================================
        // Check Participant Snapshot
        // =====================================================

        $isParticipant = EvaluationPeriodUser::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'user_id',
                $user->user_id
            )
            ->exists();

        if (!$isParticipant) {
            return User::query()
                ->whereRaw('1 = 0')
                ->paginate($perPage);
        }

        // =====================================================
        // Get Eligible Users
        // =====================================================

        $peers = User::query()
            ->where('status', 'active')

            // Department admin cannot be evaluated
            ->where('role', '!=', 'department_admin')

            // Cannot evaluate yourself
            ->where(
                'user_id',
                '!=',
                $user->user_id
            )

            // Must exist in evaluation snapshot
            ->whereHas(
                'evaluationPeriodUsers',
                function ($query) use ($evaluationPeriod) {
                    $query->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    );
                }
            )

            // Same organization
            ->where(
                'organization_id',
                $user->organization_id
            )

            // Same department
            ->where(
                'department_id',
                $user->department_id
            )

            // IMPORTANT:
            // No office_id filter.
            ->orderBy('name_kh')
            ->paginate($perPage);

        // =====================================================
        // Attach Evaluation Status
        // =====================================================

        foreach ($peers as $peer) {

            $evaluation = Evaluation::query()
                ->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                )
                ->where(
                    'evaluator_id',
                    $user->user_id
                )
                ->where(
                    'evaluatee_id',
                    $peer->user_id
                )
                ->where(
                    'evaluation_type',
                    'behavior'
                )
                ->first();

            $peer->evaluation_status =
                $evaluation?->evaluation_status;
        }

        return $peers;
    }


    /**
     * Store all behavior evaluations.
     */
    public function store(array $data): void
    {
        $evaluator = auth()->user();

        DB::transaction(function () use ($data, $evaluator) {

            // ==========================================
            // Get Current Open Evaluation Period
            // ==========================================

            $evaluationPeriod = $this->getOpenEvaluationPeriod();

            if (!$evaluationPeriod) {
                throw ValidationException::withMessages([
                    'evaluations' =>
                        'មិនមានការវាយតម្លៃដែលកំពុងបើកទេ។',
                ]);
            }


            // ==========================================
            // Check Evaluator Participant
            // ==========================================

            if (
                !$this->isParticipant(
                    $evaluationPeriod,
                    $evaluator->user_id
                )
            ) {
                throw ValidationException::withMessages([
                    'evaluations' =>
                        'អ្នកមិនមានសិទ្ធិចូលរួមក្នុងការវាយតម្លៃនេះទេ។',
                ]);
            }


            // ==========================================
            // Get Eligible Peers
            // ==========================================

            $eligiblePeers = $this->getEligiblePeers()
                ->keyBy('user_id');


            // ==========================================
            // Process Every Peer
            // ==========================================

            foreach ($data['evaluations'] as $evaluationData) {

                $evaluateeId =
                    (int) $evaluationData['evaluatee_id'];


                // ==========================================
                // Verify Peer Eligibility
                // ==========================================

                if (!$eligiblePeers->has($evaluateeId)) {

                    throw ValidationException::withMessages([
                        'evaluations' =>
                            'មន្ត្រីម្នាក់ក្នុងបញ្ជីមិនមែនជាមន្ត្រីដែលអ្នកអាចវាយតម្លៃបានទេ។',
                    ]);
                }


                $evaluatee =
                    $eligiblePeers->get($evaluateeId);


                // ==========================================
                // Calculate Behavior Total
                //
                // 10 criteria × maximum 2 points
                // = maximum 20 points
                // ==========================================

                $totalScore =
                    (int) $evaluationData['discipline']
                    + (int) $evaluationData['responsibility']
                    + (int) $evaluationData['professional_ethics']
                    + (int) $evaluationData['work_performance']
                    + (int) $evaluationData['self_development']
                    + (int) $evaluationData['initiative_creativity']
                    + (int) $evaluationData['teamwork']
                    + (int) $evaluationData['interpersonal_skill']
                    + (int) $evaluationData['work_under_pressure']
                    + (int) $evaluationData['leadership'];


                // ==========================================
                // Find Existing Evaluation
                // ==========================================

                $evaluation = Evaluation::query()
                    ->where(
                        'evaluation_period_id',
                        $evaluationPeriod->evaluation_period_id
                    )
                    ->where(
                        'evaluator_id',
                        $evaluator->user_id
                    )
                    ->where(
                        'evaluatee_id',
                        $evaluatee->user_id
                    )
                    ->where(
                        'evaluation_type',
                        'behavior'
                    )
                    ->first();


                // ==========================================
                // Create New Evaluation
                // ==========================================

                if (!$evaluation) {

                    $evaluation = Evaluation::create([

                        'evaluation_period_id' =>
                            $evaluationPeriod->evaluation_period_id,

                        'evaluator_id' =>
                            $evaluator->user_id,

                        'evaluatee_id' =>
                            $evaluatee->user_id,

                        'evaluation_type' =>
                            'behavior',

                        'evaluation_status' =>
                            'submitted',

                        'submitted_at' =>
                            now(),

                        'created_by' =>
                            $evaluator->user_id,
                    ]);
                }


                // ==========================================
                // Update Existing Evaluation
                // ==========================================
                else {

                    $evaluation->update([

                        'evaluation_status' =>
                            'submitted',

                        'submitted_at' =>
                            now(),

                        'updated_by' =>
                            $evaluator->user_id,
                    ]);
                }


                // ==========================================
                // Create / Update Behavior
                // ==========================================

                EvaluationBehavior::updateOrCreate(

                    [
                        'evaluation_id' =>
                            $evaluation->evaluation_id,
                    ],

                    [

                        'discipline' =>
                            $evaluationData['discipline'],

                        'responsibility' =>
                            $evaluationData['responsibility'],

                        'professional_ethics' =>
                            $evaluationData['professional_ethics'],

                        'work_performance' =>
                            $evaluationData['work_performance'],

                        'self_development' =>
                            $evaluationData['self_development'],

                        'initiative_creativity' =>
                            $evaluationData['initiative_creativity'],

                        'teamwork' =>
                            $evaluationData['teamwork'],

                        'interpersonal_skill' =>
                            $evaluationData['interpersonal_skill'],

                        'work_under_pressure' =>
                            $evaluationData['work_under_pressure'],

                        'leadership' =>
                            $evaluationData['leadership'],

                        'total_score' =>
                            $totalScore,
                    ]
                );
            }
        });
    }


    /**
     * Check if user belongs to evaluation participant snapshot.
     */
    private function isParticipant(
        EvaluationPeriod $evaluationPeriod,
        int $userId
    ): bool {
        return EvaluationPeriodUser::query()
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'user_id',
                $userId
            )
            ->exists();
    }


    /**
     * Check whether evaluatee is an eligible behavior target.
     *
     * This method follows the same business rule as
     * getEligiblePeers().
     */
    private function isEligiblePeer(
        User $evaluator,
        User $evaluatee
    ): bool {

        // =====================================================
        // Cannot evaluate yourself
        // =====================================================

        if (
            $evaluator->user_id ===
            $evaluatee->user_id
        ) {
            return false;
        }


        // =====================================================
        // Same Organization
        // =====================================================

        if (
            $evaluator->organization_id !==
            $evaluatee->organization_id
        ) {
            return false;
        }


        // =====================================================
        // Same Department
        // =====================================================

        if (
            $evaluator->department_id !==
            $evaluatee->department_id
        ) {
            return false;
        }


        // =====================================================
        // Evaluatee Must Be Active
        // =====================================================

        if (
            $evaluatee->status !== 'active'
        ) {
            return false;
        }


        // =====================================================
        // Department Admin Cannot Be Evaluated
        // =====================================================

        if (
            $evaluatee->role === 'department_admin'
        ) {
            return false;
        }


        // =====================================================
        // Office Does NOT Matter
        // =====================================================

        return true;
    }


    /**
     * Get submitted behavior evaluations
     * for the current evaluator.
     */
    public function getSubmittedEvaluations()
    {
        $user = auth()->user();


        // ==========================================
        // Get Open Evaluation Period
        // ==========================================

        $evaluationPeriod =
            $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return collect();
        }


        // ==========================================
        // Get Submitted Evaluations
        // ==========================================

        return Evaluation::query()
            ->with([
                'evaluatee',
                'behavior',
            ])
            ->where(
                'evaluation_period_id',
                $evaluationPeriod->evaluation_period_id
            )
            ->where(
                'evaluator_id',
                $user->user_id
            )
            ->where(
                'evaluation_status',
                'submitted'
            )
            ->where(
                'evaluation_type',
                'behavior'
            )
            ->get();
    }
}