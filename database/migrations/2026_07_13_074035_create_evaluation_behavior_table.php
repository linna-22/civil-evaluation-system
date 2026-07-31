<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('evaluation_behavior', function (Blueprint $table) {

            $table->id('behavior_id');

            $table->foreignId('evaluation_id')
                ->unique()
                ->constrained(
                    table: 'evaluations',
                    column: 'evaluation_id'
                )
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            /* ============================
             * ១. ឥរិយាបថ និងវិន័យ (៦ ពិន្ទុ)
             * ============================ */

            // គោរពវិន័យការងារ ម៉ោងពេលធ្វើការ និងបទបញ្ជាផ្ទៃក្នុងរបស់អង្គភាព
            $table->unsignedTinyInteger('discipline')->default(0);

            // ស្មារតីទទួលខុសត្រូវ
            $table->unsignedTinyInteger('responsibility')->default(0);

            // ការគោរពឋានានុក្រមការងារ និងគោរពការសម្ងាត់វិជ្ជាជីវៈ និងកាតព្វកិច្ចលក្ខណការណ៍
            $table->unsignedTinyInteger('professional_ethics')->default(0);

            /* ============================
             * ២. សមត្ថភាពវិជ្ជាជីវៈ (៦ ពិន្ទុ)
             * ============================ */

            // សមត្ថភាពបំពេញការងារ
            $table->unsignedTinyInteger('work_performance')->default(0);

            // ឆន្ទៈក្នុងការអភិវឌ្ឍសមត្ថភាព ចំណេះដឹង និងជំនាញ
            $table->unsignedTinyInteger('self_development')->default(0);

            // មានគំនិតផ្តួចផ្តើម និងច្នៃប្រឌិត
            $table->unsignedTinyInteger('initiative_creativity')->default(0);

            /* ============================
             * ៣. ភាពជាអ្នកដឹកនាំ (៨ ពិន្ទុ)
             * ============================ */

            // សហការជាមួយមន្ត្រីរាជការដទៃដើម្បីសម្រេចលទ្ធផលរួម / ស្មារតីជាក្រុម
            $table->unsignedTinyInteger('teamwork')->default(0);

            // ទំនាក់ទំនងអន្តរបុគ្គល
            $table->unsignedTinyInteger('interpersonal_skill')->default(0);

            // សមត្ថភាពអនុវត្តការងារក្រោមសម្ពាធ
            $table->unsignedTinyInteger('work_under_pressure')->default(0);

            // សមត្ថភាពភាពជាអ្នកដឹកនាំ
            $table->unsignedTinyInteger('leadership')->default(0);

            /* ============================
             * លទ្ធផលសរុប
             * ============================ */

            // ពិន្ទុសរុប (អតិបរមា ២០ ពិន្ទុ)
            $table->decimal('total_score', 5, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_behavior');
    }
};