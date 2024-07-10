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
        Schema::create('users_subscription', function (Blueprint $table) {
            $table->id();
            $table->string("uid");
            $table->foreign("uid")->references("uid")->on("users_mst")->cascadeOnDelete();
            $table->string("order_id")->nullable();
            $table->timestamp("start_timestamp");
            $table->boolean("enabled");
            $table->unsignedBigInteger("plan_id");
            $table->foreign("plan_id")->references("id")->on("subscription_plans")->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_subscription');
    }
};
