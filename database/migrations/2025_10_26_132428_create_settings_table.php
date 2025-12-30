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
        // Schema::create('settings', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // 1. معلومات التواصل الأساسية
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address_ar')->nullable();
            $table->string('address_en')->nullable();

            // 2. روابط التواصل الاجتماعي
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();

            // 3. العلامات التجارية (Branding)
            $table->string('logo_path')->nullable(); // شعار الموقع
            $table->string('favicon_path')->nullable(); // أيقونة المتصفح

            // 4. إعدادات التكامل والـ SEO المتقدمة
            $table->string('google_analytics_id')->nullable();
            $table->string('google_maps_api_key')->nullable();
            $table->text('default_meta_desc_ar')->nullable();
            $table->text('default_meta_desc_en')->nullable();
            $table->string('default_og_image_path')->nullable(); // صورة المشاركة الافتراضية

            // 5. نصوص التذييل والتحكم بالميزات
            $table->text('footer_text_ar')->nullable(); // نص حقوق النشر في التذييل
            $table->text('footer_text_en')->nullable();
            $table->boolean('maintenance_mode')->default(false); // وضع الصيانة

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
