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
        Schema::create('users_settings', function (Blueprint $table) {
            $table->id();
            $table->string("uid");
            $table->foreign("uid")->references("uid")->on("users_mst")->cascadeOnDelete();
            $table->string("title")->nullable();
            $table->string("bio")->nullable();
            $table->string("category")->nullable();
            $table->string("tab_name")->default("Products");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_settings');
    }
};
