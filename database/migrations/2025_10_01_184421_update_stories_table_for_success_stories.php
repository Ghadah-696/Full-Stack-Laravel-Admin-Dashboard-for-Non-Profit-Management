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
        Schema::table('stories', function (Blueprint $table) {
            // 1. إعادة تسمية الحقول أولاً
            $table->renameColumn('body_ar', 'content_ar');
            $table->renameColumn('body_en', 'content_en');

            // 2. إضافة حقول الهوية (اسم المستفيد)
            // (سنفترض أنها مطلوبة، لكن يمكن جعلها اختيارية بإضافة nullable())
            $table->string('name_ar')->after('id');
            $table->string('name_en')->after('name_ar');

            // 3. تعديل خصائص الحقول وجعلها اختيارية (nullable)
            $table->string('title_ar')->nullable()->change();
            $table->string('title_en')->nullable()->change();
            $table->string('image')->nullable()->change(); // جعل الصورة اختيارية

            // 4. إضافة حقول الإدارة (order, status)
            $table->integer('order')->default(0);
            $table->boolean('status')->default(true);

            // 5. حذف الحقل غير الضروري (Slug)
            $table->dropColumn('slug');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {
            // عكس الإضافات
            $table->dropColumn(['name_ar', 'name_en', 'order', 'status']);

            // عكس التعديلات (إعادة التسمية)
            $table->renameColumn('content_ar', 'body_ar');
            $table->renameColumn('content_en', 'body_en');

            // عكس الحذف (إعادة حقل Slug)
            $table->string('slug')->unique();

            // ملاحظة: عكس nullable() يجب أن يتم بحذر.
        });
    }
};
