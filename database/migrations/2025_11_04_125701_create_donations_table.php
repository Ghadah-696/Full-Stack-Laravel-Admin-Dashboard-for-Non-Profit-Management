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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            // 1. الأعمدة الأساسية والتفاصيل المالية
            $table->decimal('amount', 8, 2);
            $table->string('currency', 3);
            $table->string('payment_method')->nullable();

            // 2. علاقة المتبرع (إذا كان مسجلًا)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null')
                ->comment('المستخدم المتبرع (اذا كان مسجل)');

            // 3. الأعمدة الإدارية
            $table->string('donor_name')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');

            // 4. 🛑 أعمدة تتبع التدقيق (Audit Columns) - الأهم هنا!
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null')->comment('الموظف الذي أنشأ السجل');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->comment('الموظف الذي عدّل آخر مرة');

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
