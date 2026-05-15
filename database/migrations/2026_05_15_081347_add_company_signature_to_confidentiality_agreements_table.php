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
            $table->longText('company_signature')->nullable()->after('employee_signature');
            $table->timestamp('company_agreed_at')->nullable()->after('witness2_agreed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('confidentiality_agreements', function (Blueprint $table) {
            $table->dropColumn(['company_signature', 'company_agreed_at']);
        });
    }
};
