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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();

            // مسار الملف: لتخزين اسم ملف PDF أو DOCX
            $table->string('file_path');

            // نوع الوثيقة (للتصنيف والفلترة)
            $table->string('type')->default('مالي'); // مثال: مالي, حوكمة, استراتيجي

            // سنة الإصدار (مهم للتقارير السنوية)
            $table->integer('year')->default(date('Y'));

            // حالة الظهور
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
