<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            // ส่วนที่ 1: ประเภทคำร้อง
            $table->string('request_type')->nullable()->after('details');
            // ข้อมูลที่ snapshot มาจาก employees ตอนสร้างคำร้อง
            $table->string('emp_code')->nullable()->after('request_type');
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('nickname_th')->nullable();
            $table->string('firstname_en')->nullable();
            $table->string('lastname_en')->nullable();
            $table->string('nickname_en')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_ext')->nullable();
            $table->string('position_level')->nullable(); // ผู้บริหาร, หัวหน้าแผนก, พนักงานทั่วไป, อื่น
            $table->string('position_name')->nullable();
            $table->string('department_name')->nullable();
            $table->string('division_name')->nullable();

            // ส่วนที่ 2: การเข้าถึงระบบ (System Access)
            $table->json('system_access')->nullable(); // JSON: {login_computer, email, file_server_level, other}

            // ส่วนที่ 2: การเข้าถึงโปรแกรม (Program Access)
            $table->json('program_access')->nullable(); // JSON: {sap_b1, sap_b1_level[], rapid_payroll, other}
        });
    }

    public function down(): void
    {
        Schema::table('request_forms', function (Blueprint $table) {
            $table->dropColumn([
                'request_type', 'emp_code', 'firstname', 'lastname',
                'nickname_th', 'firstname_en', 'lastname_en', 'nickname_en',
                'phone', 'phone_ext', 'position_level', 'position_name',
                'department_name', 'division_name',
                'system_access', 'program_access',
            ]);
        });
    }
};
