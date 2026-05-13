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
        Schema::create('confidentiality_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_form_id')->constrained('request_forms')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Employee Information from the form
            $table->string('full_name');
            $table->integer('age');
            $table->string('id_card_no');
            
            // Address
            $table->string('address_no');
            $table->string('soi')->nullable();
            $table->string('road')->nullable();
            $table->string('tambon');
            $table->string('amphoe');
            $table->string('province');
            $table->string('contact_no');
            
            // Signatures
            $table->text('employee_signature')->nullable(); // Base64 or Path
            $table->text('witness1_signature')->nullable();
            $table->text('witness2_signature')->nullable();
            $table->string('witness1_name')->nullable();
            $table->string('witness2_name')->nullable();
            
            $table->timestamp('agreement_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confidentiality_agreements');
    }
};
