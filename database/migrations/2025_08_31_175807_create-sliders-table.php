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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->longText('body_ar'); // يمكن تغييرها إلى text إذا لم تكون بحاجة لطول كبير
            $table->longText('body_en'); // يمكن تغييرها إلى text إذا لم تكون بحاجة لطول كبير
            $table->string('image');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 💡 تصحيح: يجب أن يحذف الجدول عند التراجع
        Schema::dropIfExists('sliders');
    }
};
