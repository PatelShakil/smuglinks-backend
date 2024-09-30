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
        Schema::create('web_view', function (Blueprint $table) {
            $table->id();
            $table->string("ip_address")->nullable();
            $table->unsignedBigInteger("config_id")->nullable();
            $table->foreign("config_id")->references("id")->on("web_config")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_web_views');
    }
};
