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
        // 💡 هذا الملف الآن يضمن أن جدول sliders موجود، ويمكنه تعديله
        Schema::table('sliders', function (Blueprint $table) {

            // 1. إعادة تسمية الأعمدة
            $table->renameColumn('body_ar', 'description_ar');
            $table->renameColumn('body_en', 'description_en');

            // 2. تعديل خصائص الحقول الموجودة (باستخدام الأسماء الجديدة)
            // يجب أن تكون الأعمدة موجودة الآن لتعديلها
            $table->string('title_ar')->nullable()->change();
            $table->string('title_en')->nullable()->change();
            $table->text('description_ar')->nullable()->change();
            $table->text('description_en')->nullable()->change();

            // 3. إضافة الحقول الجديدة
            $table->string('link')->nullable()->after('description_en');
            $table->integer('order')->default(0)->after('link');
            $table->boolean('status')->default(true)->after('order');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // إذا كنتِ تريدين التراجع، يجب أن يتم حذف الأعمدة الجديدة
            $table->dropColumn(['link', 'order', 'status']);

            // وإعادة تسمية الأعمدة إلى حالتها القديمة
            $table->renameColumn('description_ar', 'body_ar');
            $table->renameColumn('description_en', 'body_en');
        });
    }
};
