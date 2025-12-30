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
        Schema::table('users', function (Blueprint $table) {
            // 💡 إضافة الحقل: 'profile_photo_path' من نوع سلسلة، يمكن أن يكون فارغاً (nullable).
            $table->string('profile_photo_path', 2048)->nullable()->after('password');
            // وضعناه بعد حقل 'password' للترتيب، ويمكنك اختيار مكان آخر.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 💡 عند التراجع، نحذف الحقل المضاف
            $table->dropColumn('profile_photo_path');
        });
    }
};
