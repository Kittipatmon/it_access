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
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('employee_code', 'emp_code');
            $table->renameColumn('first_name_th', 'firstname');
            $table->renameColumn('last_name_th', 'lastname');
            $table->renameColumn('department_id', 'dept_id');
            $table->string('username')->nullable()->after('email');
            $table->string('status_temp')->default('active')->after('is_active');
        });

        // Copy data and drop old column if needed, but for now let's just make it compatible
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table->renameColumn('status_temp', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
