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
        Schema::create('products_mst', function (Blueprint $table) {
            $table->id();
            $table->string("uid");
            $table->foreign("uid")->references("uid")->on("users_mst")->cascadeOnDelete();
            $table->string("name");
            $table->string("description")->nullable();
            $table->string('category');
            $table->string('action')->nullable();
            $table->string('link')->nullable();
            $table->string('btn_name')->nullable();
            $table->boolean("enabled")->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_mst');
    }
};
