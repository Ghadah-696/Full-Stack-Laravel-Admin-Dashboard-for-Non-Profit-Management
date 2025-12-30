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
        Schema::table('news', function (Blueprint $table) {
            // 1. إضافة حقل category_id (المفتاح الأجنبي)
            // nullable(): يعني أنه يمكن أن يكون فارغاً مؤقتاً (مهم أثناء التعديل أو إذا لم يكن التصنيف إلزامياً).
            // constrained('categories'): يربطه بجدول categories.
            $table->foreignId('category_id')->nullable()->constrained('categories')->after('image');

            // 2. إضافة حقل status (الحالة)
            // default(false): القيمة الافتراضية هي 'مسودة' (false).
            $table->boolean('status')->default(false)->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['category_id']);

            // ثم حذف الأعمدة نفسها
            $table->dropColumn(['category_id', 'status']);
            //
        });
    }
};
