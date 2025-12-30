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
        Schema::table('pages', function (Blueprint $table) {
            // حقل الأب (مفتاح خارجي يمكن أن يكون فارغاً إذا كانت صفحة رئيسية مثل 'من نحن')
            $table->foreignId('parent_id')->nullable()->constrained('pages')->onDelete('cascade')->after('slug');

            // حقل الترتيب (لتحديد ترتيب ظهور العناصر في القائمة الجانبية)
            $table->integer('order')->default(0)->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']); // حذف المفتاح الخارجي أولاً
            $table->dropColumn('parent_id');
            $table->dropColumn('order');
        });
    }
};
