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
        Schema::table('confidentiality_agreements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['witness1_user_id']);
            $table->dropForeign(['witness2_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confidentiality_agreements', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('witness1_user_id')->references('id')->on('users');
            $table->foreign('witness2_user_id')->references('id')->on('users');
        });
    }
};
