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
     * Get eligible peers for the current user.
     */
    public function getEligiblePeers()
    {
        $user = auth()->user();
        if ($user->role !== 'user') {
            abort(403);
        }
        // ==========================================
        // Leaders do not participate in evaluation
        // ==========================================

        if ($user->is_leader) {
            return collect();
        }
        // ==========================================
        // Get Open Evaluation Period
        // ==========================================

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return collect();
        }


        // ==========================================
        // Check Participant Snapshot
        // ==========================================

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


        // ==========================================
        // Base Peer Query
        // ==========================================

        $query = User::query()
            ->where('status', 'active')
            ->where('is_leader', 0)
            // Never evaluate yourself
            ->where(
                'user_id',
                '!=',
                $user->user_id
            )

            // Must be part of evaluation snapshot
            ->whereHas('evaluationPeriodUsers', function ($query) use ($evaluationPeriod) {
                $query->where(
                    'evaluation_period_id',
                    $evaluationPeriod->evaluation_period_id
                );
            })

            // ==========================================
            // Same Organization
            // ==========================================

            ->where(
                'organization_id',
                $user->organization_id
            )

            // ==========================================
            // Same Department
            // ==========================================

            ->where(
                'department_id',
                $user->department_id
            );


        // ==========================================
        // Same Office
        // ==========================================

        if ($user->office_id) {

            $query->where(
                'office_id',
                $user->office_id
            );
        }


        $peers = $query
            ->orderBy('name_kh')
            ->get();


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
                $evaluateeId = (int) $evaluationData['evaluatee_id'];
                // ==========================================
                // Verify Peer Eligibility
                // ==========================================

                if (!$eligiblePeers->has($evaluateeId)) {

                    throw ValidationException::withMessages([
                        'evaluations' =>
                            'មន្ត្រីម្នាក់ក្នុងបញ្ជីមិនមែនជាមន្ត្រីដែលអ្នកអាចវាយតម្លៃបានទេ។',
                    ]);
                }

                $evaluatee = $eligiblePeers->get($evaluateeId);
                // ==========================================
                // Calculate Behavior Total
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
                    ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
                    ->where('evaluator_id', $evaluator->user_id)
                    ->where('evaluatee_id', $evaluatee->user_id)
                    ->first();

                // ==========================================
                // Create New Evaluation
                // ==========================================

                if (!$evaluation) {

                    $evaluation = Evaluation::create([

                        'evaluation_period_id' => $evaluationPeriod->evaluation_period_id,
                        'evaluator_id' => $evaluator->user_id,
                        'evaluatee_id' => $evaluatee->user_id,
                        'evaluation_type' => 'behavior',
                        'evaluation_status' => 'submitted',
                        'submitted_at' => now(),
                        'created_by' => $evaluator->user_id,
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

                        'discipline' => $evaluationData['discipline'],
                        'responsibility' => $evaluationData['responsibility'],
                        'professional_ethics' => $evaluationData['professional_ethics'],
                        'work_performance' => $evaluationData['work_performance'],
                        'self_development' => $evaluationData['self_development'],
                        'initiative_creativity' => $evaluationData['initiative_creativity'],
                        'teamwork' => $evaluationData['teamwork'],
                        'interpersonal_skill' => $evaluationData['interpersonal_skill'],
                        'work_under_pressure' => $evaluationData['work_under_pressure'],
                        'leadership' => $evaluationData['leadership'],
                        'total_score' => $totalScore,
                    ]
                );

            }

        });
    }

    /**
     * Check if user belongs to evaluation participant snapshot.
     */
    private function isParticipant(EvaluationPeriod $evaluationPeriod, int $userId): bool
    {
        return EvaluationPeriodUser::query()->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
            ->where(
                'user_id',
                $userId
            )
            ->exists();
    }


    /**
     * Check whether evaluatee is an eligible peer.
     */
    private function isEligiblePeer(User $evaluator, User $evaluatee): bool
    {
        // ==========================================
        // Cannot evaluate yourself
        // ==========================================
        if ($evaluator->user_id === $evaluatee->user_id) {
            return false;
        }
        // ==========================================
        // Same Organization
        // ==========================================
        if ($evaluator->organization_id !== $evaluatee->organization_id) {
            return false;
        }
        // ==========================================
        // Same Department
        // ==========================================
        if ($evaluator->department_id !== $evaluatee->department_id) {
            return false;
        }
        // ==========================================
        // Same Office
        // ==========================================
        if ($evaluator->office_id) {
            return $evaluator->office_id === $evaluatee->office_id;
        }
        // ==========================================
        // If Evaluator Has No Office
        // ==========================================
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

        $evaluationPeriod = $this->getOpenEvaluationPeriod();

        if (!$evaluationPeriod) {
            return collect();
        }


        // ==========================================
        // Get Submitted Evaluations
        // ==========================================

        return Evaluation::query()
            ->with(['evaluatee', 'behavior',])
            ->where('evaluation_period_id', $evaluationPeriod->evaluation_period_id)
            ->where('evaluator_id', $user->user_id)
            ->where('evaluation_status', 'submitted')
            ->where('evaluation_type', 'behavior')
            ->get();
    }   
}