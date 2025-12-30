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

        Schema::table('categories', function (Blueprint $table) {
            // إضافة حقل الوصف باللغة العربية. nullable لعدم تعطيل أي بيانات سابقة.
            $table->text('description_ar')->nullable()->after('name_en')->comment('وصف التصنيف باللغة العربية');

            // إضافة حقل الوصف باللغة الإنجليزية.
            $table->text('description_en')->nullable()->after('description_ar')->comment('وصف التصنيف باللغة الإنجليزية');

            // إضافة حقل النوع (Type) لغرض التصنيف الداخلي (Main/Tag) إذا لم يكن موجوداً
            if (!Schema::hasColumn('categories', 'type')) {
                $table->string('type')->default('Main')->after('description_en')->comment('نوع التصنيف');
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // حذف الأعمدة في حال التراجع
            $table->dropColumn(['description_ar', 'description_en', 'type']);
        });
    }
};
