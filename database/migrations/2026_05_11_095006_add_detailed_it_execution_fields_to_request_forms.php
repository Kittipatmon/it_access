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
        Schema::table('request_forms', function (Blueprint $table) {
            $table->json('it_system_config')->nullable()->after('it_status');
            $table->json('it_program_config')->nullable()->after('it_system_config');
            $table->string('user_signature_path')->nullable()->after('it_program_config'); // For Part 4
            $table->timestamp('user_acknowledged_at')->nullable()->after('user_signature_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropColumn(['it_system_config', 'it_program_config', 'user_signature_path', 'user_acknowledged_at']);
        });
    }
};
