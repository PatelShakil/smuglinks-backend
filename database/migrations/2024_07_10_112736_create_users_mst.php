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
        Schema::create('users_mst', function (Blueprint $table) {
            $table->string("uid")->primary(true);
            $table->string("username")->unique();
            $table->string("name")->nullable();
            $table->string("email")->unique();
            $table->string("phone")->nullable();
            $table->string("profile")->nullable();
            $table->boolean("active")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_mst');
    }
};
