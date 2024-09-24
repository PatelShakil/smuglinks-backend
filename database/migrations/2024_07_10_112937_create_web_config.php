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
            $table->unsignedBigInteger("font_id")->nullable();
            $table->foreign("font_id")->references("id")->on("web_fonts")->cascadeOnDelete();
            $table->string("font_color")->nullable();
            $table->unsignedBigInteger("theme_id")->nullable();
            $table->foreign("theme_id")->references("id")->on("web_themes")->cascadeOnDelete();
            $table->string("btn_type")->nullable();
            $table->string("btn_border_type")->nullable();
            $table->string("btn_curve_type")->nullable();
            $table->string("btn_font_color")->nullable();
            $table->string("btn_color")->nullable();
            $table->integer("bg_type")->nullable();//1->color, 2->gradient, 3-> image
            $table->string("bg_color")->nullable();
            $table->string("start_color")->nullable();
            $table->string("end_color")->nullable();
            $table->string("bg_img")->nullable();
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
