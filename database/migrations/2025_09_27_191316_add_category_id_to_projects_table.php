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
        // هذا السطر يخبر Laravel أننا سنقوم بتعديل (Table) جدول موجود
        Schema::table('projects', function (Blueprint $table) {
            // هنا يتم إضافة الأعمدة لجدول projects الحالي
            $table->foreignId('category_id')->nullable()->constrained('categories')->after('image');
            // إضافة حقول التواريخ والحالة
            $table->date('start_date')->nullable()->after('category_id');
            $table->date('end_date')->nullable()->after('start_date');
            $table->boolean('status')->default(true)->after('end_date');
            // $table->boolean('status')->default(false)->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // حذف المفتاح الأجنبي أولاً
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'start_date', 'end_date', 'status']);
        });

    }
};
