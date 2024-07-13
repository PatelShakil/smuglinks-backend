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
            $table->string("email")->unique();
            $table->string("phone");
            $table->string("password");
            $table->string("profile")->nullable();
            $table->boolean("active");
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
