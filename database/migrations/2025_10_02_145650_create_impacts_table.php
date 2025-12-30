<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('impacts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')->constrained()->onDelete('cascade');

            // تتبع الأرقام والتمويل
            $table->decimal('required_amount', 10, 2);
            $table->decimal('raised_amount', 10, 2)->default(0.00);

            // 💡 الحقل الجديد: نسبة الإنجاز
            $table->integer('progress_percentage')->default(0);

            // الهدف الكمي
            $table->string('goal_ar');
            $table->string('goal_en');
            $table->string('reached_ar');
            $table->string('reached_en');

            $table->boolean('status')->default(true);

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('impacts');
    }
};
