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
        Schema::create('links_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("link_id");
            $table->foreign("link_id")->references("id")->on("links_mst")->cascadeOnDelete();
            $table->integer("clicks");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links_analytics');
    }
};
