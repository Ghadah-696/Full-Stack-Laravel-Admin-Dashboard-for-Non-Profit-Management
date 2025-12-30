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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar', 100)->unique(); // اسم التصنيف بالعربية (يجب أن يكون فريداً)
            $table->string('name_en', 100)->nullable(); // اسم التصنيف بالإنجليزية
            $table->string('slug', 150)->unique(); // رابط مختصر للتصنيف (URL-friendly)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
