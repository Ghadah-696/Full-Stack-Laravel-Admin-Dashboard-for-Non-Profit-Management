<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Unique;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //create stories fields
        Schema::create('stories' , function(Blueprint $table){
            $table->id();
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('slug')->Unique();
            $table->longText('body_ar');
            $table->longText('body_en');
            $table->string('image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
