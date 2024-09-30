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
        Schema::create('link_view', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("link_id");
            $table->foreign("link_id")->references("id")->on("links_mst")->cascadeOnDelete();
            $table->string("ip_address")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links_view');
    }
};
