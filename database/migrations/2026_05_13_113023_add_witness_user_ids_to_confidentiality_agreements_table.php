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
            $table->foreignId('witness1_user_id')->nullable()->after('witness2_name')->constrained('users');
            $table->foreignId('witness2_user_id')->nullable()->after('witness1_user_id')->constrained('users');
            $table->timestamp('witness1_agreed_at')->nullable()->after('witness2_user_id');
            $table->timestamp('witness2_agreed_at')->nullable()->after('witness1_agreed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confidentiality_agreements', function (Blueprint $table) {
            $table->dropForeign(['witness1_user_id']);
            $table->dropForeign(['witness2_user_id']);
            $table->dropColumn(['witness1_user_id', 'witness2_user_id', 'witness1_agreed_at', 'witness2_agreed_at']);
        });
    }
};
