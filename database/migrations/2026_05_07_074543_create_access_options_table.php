<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('access_options', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // system, program
            $table->string('name');     // ชื่อรายการ เช่น Username & Password Login Computer
            $table->string('key')->unique(); // key สำหรับ reference เช่น login_computer
            $table->boolean('has_sub_options')->default(false); // มี sub-options หรือไม่
            $table->json('sub_options')->nullable(); // รายการ sub-options เช่น ["Super User","Admin","User"]
            $table->string('sub_option_type')->default('radio'); // radio หรือ checkbox
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default data
        $defaults = [
            // การเข้าถึงระบบ
            ['category' => 'system', 'name' => 'Username & Password Login Computer', 'key' => 'login_computer', 'has_sub_options' => false, 'sub_options' => null, 'sub_option_type' => 'radio', 'sort_order' => 1],
            ['category' => 'system', 'name' => 'Email Address', 'key' => 'email', 'has_sub_options' => false, 'sub_options' => null, 'sub_option_type' => 'radio', 'sort_order' => 2],
            ['category' => 'system', 'name' => 'File Server Access', 'key' => 'file_server', 'has_sub_options' => true, 'sub_options' => json_encode(['Super User', 'Admin', 'User']), 'sub_option_type' => 'radio', 'sort_order' => 3],
            // การเข้าถึงโปรแกรม
            ['category' => 'program', 'name' => 'SAP B1 Username & Password', 'key' => 'sap_b1', 'has_sub_options' => false, 'sub_options' => null, 'sub_option_type' => 'radio', 'sort_order' => 1],
            ['category' => 'program', 'name' => 'SAP B1: level', 'key' => 'sap_b1_level', 'has_sub_options' => true, 'sub_options' => json_encode(['Pro', 'CRM', 'Logistics', 'Financials']), 'sub_option_type' => 'checkbox', 'sort_order' => 2],
            ['category' => 'program', 'name' => 'Rapid Payroll', 'key' => 'rapid_payroll', 'has_sub_options' => false, 'sub_options' => null, 'sub_option_type' => 'radio', 'sort_order' => 3],
        ];

        foreach ($defaults as $item) {
            DB::table('access_options')->insert(array_merge($item, [
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('access_options');
    }
};
