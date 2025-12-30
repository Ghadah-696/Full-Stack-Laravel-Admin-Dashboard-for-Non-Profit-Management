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

            // 1. أعمدة الـ SEO
            $table->string('meta_title_ar')->nullable()->after('body_en');
            $table->string('meta_title_en')->nullable()->after('meta_title_ar');
            $table->text('meta_description_ar')->nullable()->after('meta_title_en');
            $table->text('meta_description_en')->nullable()->after('meta_description_ar');
            $table->string('keywords_ar')->nullable()->after('meta_description_en');
            $table->string('keywords_en')->nullable()->after('keywords_ar');

            // 2. أعمدة الصورة والحالة (إذا لم تكن مضافة بعد)
            $table->string('banner_image_path')->nullable()->after('keywords_en');
            $table->boolean('status')->default(true)->after('banner_image_path');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            //
        });
    }
};
