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
        // Modifying the users_mst table to add the password column after the email column
        Schema::table('users_mst', function (Blueprint $table) {
            $table->string('password')->after('email'); // Add password field after email
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Dropping the password column when rolling back
        Schema::table('users_mst', function (Blueprint $table) {
            $table->dropColumn('password');
        });
    }
};
