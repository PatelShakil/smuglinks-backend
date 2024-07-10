<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('web_themes', function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->string("img");
            $table->string("name");
            $table->string("description");
            $table->boolean("enabled");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_themes');
    }
};
