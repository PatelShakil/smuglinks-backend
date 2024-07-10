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
        Schema::create('web_config', function (Blueprint $table) {
            $table->id();
            $table->string("uid");
            $table->foreign("uid")->references("uid")->on("users_mst")->cascadeOnDelete();
            $table->unsignedBigInteger("font_id");
            $table->foreign("font_id")->references("id")->on("web_fonts")->cascadeOnDelete();
            $table->unsignedBigInteger("button_id");
            $table->foreign("button_id")->references("id")->on("web_buttons")->cascadeOnDelete();
            $table->unsignedBigInteger("theme_id");
            $table->foreign("theme_id")->references("id")->on("web_themes")->cascadeOnDelete();
            $table->boolean("is_gradient");
            $table->string("start_color");
            $table->string("end_color");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_config');
    }
};
