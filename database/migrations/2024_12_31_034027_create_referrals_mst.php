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
        Schema::create('referrals_mst', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code', 50)->unique();
            $table->string('uid');
            $table->foreign("uid")->references("uid")->on("admin_mst")->cascadeOnDelete();
            $table->boolean('enabled');
            $table->string('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals_mst');
    }
};
