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
        Schema::create('links_mst', function (Blueprint $table) {
            $table->id();
            $table->string("uid");
            $table->foreign("uid")->references("uid")->on("users_mst")->cascadeOnDelete();
            $table->string("name");
            $table->string("type");
            $table->string("image");
            $table->boolean("enabled");
            $table->integer("priority")->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links_mst');
    }
};
